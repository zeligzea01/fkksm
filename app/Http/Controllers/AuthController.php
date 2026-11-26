<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'login' => ['required', 'string', 'max:190'],
            'password' => ['required', 'string', 'max:255'],
        ]);

        $loginInput = strtolower(trim($credentials['login']));
        $fieldType = filter_var($loginInput, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $user = User::where($fieldType, $loginInput)->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            ActivityLog::record(
                null,
                'login_failed',
                "Percobaan login gagal dengan identitas: {$loginInput}"
            );

            return back()->withErrors([
                'login' => 'Nama pengguna/email atau kata sandi yang Anda masukkan salah.',
            ])->onlyInput('login');
        }

        if ($user->status !== 'active') {
            return back()->withErrors([
                'login' => 'Akun Anda berstatus nonaktif atau ditangguhkan. Silakan hubungi pengurus FKKSM.',
            ])->onlyInput('login');
        }

        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        $user->update(['last_login_at' => now()]);

        ActivityLog::record(
            $user->id,
            'login',
            "Pengguna {$user->name} ({$user->role_name}) berhasil masuk."
        );

        return redirect()->intended(route('lms.dashboard'))->with('success', "Selamat datang kembali, {$user->name}!");
    }

    public function showRegister(): View
    {
        return view('auth.register');
    }

    public function register(Request $request): RedirectResponse
    {
        $request->merge([
            'username' => strtolower(trim((string) $request->input('username', ''))),
            'email' => strtolower(trim((string) $request->input('email', ''))),
        ]);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:140'],
            'username' => ['required', 'string', 'min:3', 'max:60', 'unique:users,username', 'regex:/^[a-z0-9._-]+$/'],
            'email' => ['required', 'string', 'email', 'max:190', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $user = User::create([
            'name' => trim($validated['name']),
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'student',
            'status' => 'active',
            'last_login_at' => now(),
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        ActivityLog::record(
            $user->id,
            'register',
            "Pendaftaran akun baru: {$user->name} ({$user->username} / {$user->email})"
        );

        return redirect()->route('lms.dashboard')->with('success', 'Akun Anda berhasil dibuat. Selamat datang di FKKSM LMS!');
    }

    public function logout(Request $request): RedirectResponse
    {
        $user = Auth::user();
        if ($user) {
            ActivityLog::record($user->id, 'logout', "Pengguna {$user->name} keluar dari sistem.");
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('info', 'Anda telah berhasil keluar.');
    }

    public function changePassword(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $user = Auth::user();
        $user->password = Hash::make($validated['password']);
        $user->must_change_password = false;
        $user->setRememberToken(Str::random(60));
        $user->save();

        $request->session()->regenerate();
        if (config('session.driver') === 'database') {
            DB::table(config('session.table', 'sessions'))
                ->where('user_id', $user->id)
                ->where('id', '!=', $request->session()->getId())
                ->delete();
        }

        ActivityLog::record($user->id, 'password_changed', 'Kata sandi akun diperbarui dan seluruh sesi lain dihentikan.');

        return back()->with('success', 'Kata sandi Anda berhasil diperbarui.');
    }
}

