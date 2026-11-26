<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class SsoController extends Controller
{
    private const SESSION_STATE_KEY = 'sso.google.state';
    private const SESSION_NONCE_KEY = 'sso.google.nonce';
    private const SESSION_PKCE_VERIFIER_KEY = 'sso.google.code_verifier';

    /**
     * Initiate hardened OAuth2 / OIDC + PKCE authorization flow with Google Identity.
     */
    public function redirectToGoogle(Request $request): RedirectResponse
    {
        $clientId = (string) config('services.google.client_id');
        $redirectUri = (string) config('services.google.redirect');

        if ($clientId === '' || (string) config('services.google.client_secret') === '' || $redirectUri === '') {
            return redirect()->route('login')->with(
                'warning',
                'Layanan SSO Google belum dikonfigurasi pada server (.env: GOOGLE_CLIENT_ID & GOOGLE_CLIENT_SECRET). Silakan masuk menggunakan Email / Username Anda.'
            );
        }

        $state = Str::random(40);
        $nonce = Str::random(40);
        $codeVerifier = Str::random(64);
        $codeChallenge = rtrim(strtr(base64_encode(hash('sha256', $codeVerifier, true)), '+/', '-_'), '=');

        $request->session()->put([
            self::SESSION_STATE_KEY => $state,
            self::SESSION_NONCE_KEY => $nonce,
            self::SESSION_PKCE_VERIFIER_KEY => $codeVerifier,
        ]);

        $query = http_build_query([
            'client_id' => $clientId,
            'redirect_uri' => $redirectUri,
            'response_type' => 'code',
            'scope' => 'openid email profile',
            'state' => $state,
            'nonce' => $nonce,
            'code_challenge' => $codeChallenge,
            'code_challenge_method' => 'S256',
            'prompt' => 'select_account',
        ]);

        return redirect()->away('https://accounts.google.com/o/oauth2/v2/auth?' . $query);
    }

    /**
     * Validate CSRF state, PKCE verifier, OIDC nonce, email verification & IdP sub isolation on callback.
     */
    public function handleGoogleCallback(Request $request): RedirectResponse
    {
        if ($request->filled('error')) {
            $this->clearSsoSession($request);
            ActivityLog::record(null, 'login_failed', 'Otorisasi SSO Google dibatalkan atau gagal: ' . (string) $request->query('error'));
            return redirect()->route('login')->with('error', 'Autentikasi SSO Google dibatalkan atau ditolak oleh penyedia identitas.');
        }

        $expectedState = (string) $request->session()->pull(self::SESSION_STATE_KEY, '');
        $expectedNonce = (string) $request->session()->pull(self::SESSION_NONCE_KEY, '');
        $codeVerifier = (string) $request->session()->pull(self::SESSION_PKCE_VERIFIER_KEY, '');
        $receivedState = (string) $request->query('state', '');
        $code = (string) $request->query('code', '');

        if ($expectedState === '' || $receivedState === '' || !hash_equals($expectedState, $receivedState) || $code === '' || $codeVerifier === '') {
            ActivityLog::record(null, 'login_failed', 'Callback SSO Google gagal validasi state/CSRF atau parameter tidak lengkap.');
            return redirect()->route('login')->with('error', 'Sesi otorisasi SSO tidak valid atau kedaluwarsa. Silakan coba masuk kembali.');
        }

        $clientId = (string) config('services.google.client_id');
        $clientSecret = (string) config('services.google.client_secret');
        $redirectUri = (string) config('services.google.redirect');

        if ($clientId === '' || $clientSecret === '' || $redirectUri === '') {
            return redirect()->route('login')->with('error', 'Konfigurasi SSO server belum lengkap.');
        }

        $tokenResponse = Http::timeout(10)
            ->asForm()
            ->post('https://oauth2.googleapis.com/token', [
                'code' => $code,
                'client_id' => $clientId,
                'client_secret' => $clientSecret,
                'redirect_uri' => $redirectUri,
                'grant_type' => 'authorization_code',
                'code_verifier' => $codeVerifier,
            ]);

        if (!$tokenResponse->successful()) {
            ActivityLog::record(null, 'login_failed', 'Pertukaran kode otorisasi SSO Google gagal (HTTP ' . $tokenResponse->status() . ').');
            return redirect()->route('login')->with('error', 'Gagal memverifikasi token SSO ke server Google.');
        }

        $tokenPayload = $tokenResponse->json();
        $accessToken = (string) ($tokenPayload['access_token'] ?? '');
        $idToken = (string) ($tokenPayload['id_token'] ?? '');

        if ($accessToken === '' && $idToken === '') {
            ActivityLog::record(null, 'login_failed', 'Respon token SSO Google tidak menyertakan token valid.');
            return redirect()->route('login')->with('error', 'Token autentikasi dari penyedia SSO tidak valid.');
        }

        // Verify OIDC claims if id_token is issued (enforces aud & nonce replay protection)
        if ($idToken !== '') {
            $claims = $this->decodeJwtPayload($idToken);
            if (!is_array($claims)) {
                ActivityLog::record(null, 'login_failed', 'Payload ID token SSO rusak atau tidak dapat dibaca.');
                return redirect()->route('login')->with('error', 'Format ID Token SSO tidak valid.');
            }

            $iss = (string) ($claims['iss'] ?? '');
            $aud = (string) ($claims['aud'] ?? '');
            $nonce = (string) ($claims['nonce'] ?? '');

            if (!in_array($iss, ['https://accounts.google.com', 'accounts.google.com'], true)) {
                ActivityLog::record(null, 'login_failed', 'Issuer SSO tidak valid: ' . $iss);
                return redirect()->route('login')->with('error', 'Penerbit token SSO tidak dikenali.');
            }
            if (!hash_equals($clientId, $aud)) {
                ActivityLog::record(null, 'login_failed', 'Audience SSO tidak cocok (token confusion attempt).');
                return redirect()->route('login')->with('error', 'Token SSO tidak ditujukan untuk portal FKKSM ini.');
            }
            if ($expectedNonce !== '' && ($nonce === '' || !hash_equals($expectedNonce, $nonce))) {
                ActivityLog::record(null, 'login_failed', 'Nonce ID token SSO tidak cocok (potensi replay attack).');
                return redirect()->route('login')->with('error', 'Validasi nonce keamanan SSO gagal. Silakan ulangi login.');
            }
        }

        // Fetch verified user identity directly from Google UserInfo endpoint over TLS
        $userInfoResponse = Http::timeout(10)
            ->withToken($accessToken)
            ->get('https://openidconnect.googleapis.com/v1/userinfo');

        if (!$userInfoResponse->successful()) {
            ActivityLog::record(null, 'login_failed', 'Gagal mengambil profil pengguna terverifikasi dari penyedia SSO.');
            return redirect()->route('login')->with('error', 'Gagal membaca data akun Google Anda.');
        }

        $profile = $userInfoResponse->json();
        $sub = trim((string) ($profile['sub'] ?? ''));
        $email = strtolower(trim((string) ($profile['email'] ?? '')));
        $emailVerified = (bool) ($profile['email_verified'] ?? false);
        $name = trim((string) ($profile['name'] ?? ''));
        $avatar = filter_var((string) ($profile['picture'] ?? ''), FILTER_VALIDATE_URL) ?: null;

        if ($sub === '' || $email === '' || !$emailVerified) {
            ActivityLog::record(null, 'login_failed', 'Akun Google tidak memiliki email terverifikasi untuk SSO.');
            return redirect()->route('login')->with('error', 'Akun Google Anda wajib memiliki alamat email yang terverifikasi untuk masuk ke portal.');
        }

        $allowedDomains = array_filter(array_map('trim', explode(',', (string) config('services.google.allowed_domains', ''))));
        if (!empty($allowedDomains)) {
            $domain = Str::after($email, '@');
            if (!in_array(strtolower($domain), array_map('strtolower', $allowedDomains), true)) {
                ActivityLog::record(null, 'login_failed', "Domain SSO ditolak kebijakan akses: {$email}");
                return redirect()->route('login')->with('error', 'Domain email Anda belum diizinkan untuk login SSO FKKSM.');
            }
        }

        // Lookup strictly by IdP-isolated subject first, then verified email
        $user = User::where('google_sub', $sub)->first();
        if (!$user) {
            $user = User::where('email', $email)->first();
            if ($user) {
                if ($user->google_sub && $user->google_sub !== $sub) {
                    ActivityLog::record($user->id, 'login_failed', 'Konflik binding google_sub terdeteksi pada login SSO.');
                    return redirect()->route('login')->with('error', 'Identitas SSO tidak cocok dengan akun terdaftar.');
                }
                $user->update([
                    'google_sub' => $sub,
                    'email_verified_at' => $user->email_verified_at ?? now(),
                    'avatar' => $user->avatar ?? $avatar,
                ]);
            } else {
                $baseUsername = Str::slug(Str::before($email, '@'), '_') ?: 'peserta';
                $username = substr($baseUsername, 0, 48) . '_' . strtolower(Str::random(4));
                while (User::where('username', $username)->exists()) {
                    $username = substr($baseUsername, 0, 46) . '_' . strtolower(Str::random(6));
                }

                $user = User::create([
                    'username' => $username,
                    'name' => $name !== '' ? mb_substr($name, 0, 140) : ucfirst(Str::before($email, '@')),
                    'email' => $email,
                    'google_sub' => $sub,
                    'avatar' => $avatar,
                    'role' => 'student',
                    'status' => 'active',
                    'email_verified_at' => now(),
                    'password' => Hash::make(Str::random(32)),
                    'last_login_at' => now(),
                ]);

                ActivityLog::record($user->id, 'register', "Registrasi otomatis SSO Google: {$user->name} ({$user->email})");
            }
        }

        if ($user->status !== 'active') {
            ActivityLog::record($user->id, 'login_failed', "Percobaan login SSO akun nonaktif: {$user->email}");
            return redirect()->route('login')->with('error', 'Akun Anda berstatus nonaktif atau ditangguhkan. Silakan hubungi pengurus FKKSM.');
        }

        Auth::login($user);
        $request->session()->regenerate();
        $user->update(['last_login_at' => now()]);

        ActivityLog::record($user->id, 'sso_login', "Pengguna {$user->name} ({$user->role_name}) berhasil masuk melalui SSO Google.");

        return redirect()->intended(route('lms.dashboard'))
            ->with('success', "Selamat datang kembali melalui SSO Google, {$user->name}!");
    }

    private function clearSsoSession(Request $request): void
    {
        $request->session()->forget([
            self::SESSION_STATE_KEY,
            self::SESSION_NONCE_KEY,
            self::SESSION_PKCE_VERIFIER_KEY,
        ]);
    }

    private function decodeJwtPayload(string $jwt): ?array
    {
        $parts = explode('.', $jwt);
        if (count($parts) !== 3) {
            return null;
        }
        $decoded = base64_decode(strtr($parts[1], '-_', '+/'), true);
        if ($decoded === false) {
            return null;
        }
        $json = json_decode($decoded, true);
        return is_array($json) ? $json : null;
    }
}

