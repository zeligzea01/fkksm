<?php
declare(strict_types=1);

$secureCookie = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
session_name('fkksm_lms_session');
session_set_cookie_params(array('httponly' => true, 'samesite' => 'Lax', 'secure' => $secureCookie, 'path' => '/'));
session_start();
header('Referrer-Policy: no-referrer-when-downgrade');
require __DIR__ . '/config.php';

$pdo = null;
$databaseError = '';
try {
    $pdo = new PDO(
        'mysql:host=' . LMS_DB_HOST . ';port=' . LMS_DB_PORT . ';dbname=' . LMS_DB_NAME . ';charset=utf8mb4',
        LMS_DB_USER,
        LMS_DB_PASS,
        array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, PDO::ATTR_EMULATE_PREPARES => false)
    );
} catch (PDOException $exception) {
    $databaseError = 'Basis data belum siap. Impor database/schema.sql melalui phpMyAdmin, lalu periksa pengaturan di config.php.';
}

function h($value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function app_db(): PDO
{
    global $pdo;
    if (!$pdo instanceof PDO) {
        throw new RuntimeException('Koneksi basis data belum tersedia.');
    }
    return $pdo;
}

function flash(string $type, string $message): void
{
    $_SESSION['lms_flash'] = array('type' => $type, 'message' => $message);
}

function take_flash(): ?array
{
    if (!isset($_SESSION['lms_flash'])) {
        return null;
    }
    $message = $_SESSION['lms_flash'];
    unset($_SESSION['lms_flash']);
    return $message;
}

function csrf_token(): string
{
    if (empty($_SESSION['lms_csrf'])) {
        $_SESSION['lms_csrf'] = bin2hex(random_bytes(32));
    }
    return (string)$_SESSION['lms_csrf'];
}

function csrf_input(): string
{
    return '<input type="hidden" name="csrf" value="' . h(csrf_token()) . '">';
}

function verify_csrf(): void
{
    $sent = (string)($_POST['csrf'] ?? '');    if (empty($_POST['csrf']) && empty($_POST) && (int)($_SERVER['CONTENT_LENGTH'] ?? 0) > 0) {
        throw new RuntimeException('Ukuran unggahan melebihi batas server. Maksimal video 200 MB; periksa pengaturan upload PHP XAMPP.');
    }
    if ($sent === '' || !hash_equals(csrf_token(), $sent)) {
        throw new RuntimeException('Sesi formulir sudah kedaluwarsa. Muat ulang halaman lalu coba lagi.');
    }
}

function go(string $page = 'dashboard', array $params = array()): void
{
    $params = array_merge(array('page' => $page), $params);
    header('Location: index.php?' . http_build_query($params));
    exit;
}

function is_admin(array $user): bool
{
    return in_array($user['role'], array('super_admin', 'admin'), true);
}

function role_name(string $role): string
{
    $names = array('super_admin' => 'Administrator Utama', 'admin' => 'Administrator', 'instructor' => 'Instruktur', 'student' => 'Peserta', 'company' => 'Perusahaan');
    return $names[$role] ?? ucfirst(str_replace('_', ' ', $role));
}

function status_name(string $status): string
{
    $names = array(
        'active' => 'Aktif', 'inactive' => 'Tidak aktif', 'pending' => 'Menunggu',
        'suspended' => 'Ditangguhkan', 'draft' => 'Draf', 'published' => 'Terbit',
        'archived' => 'Diarsipkan', 'paused' => 'Dijeda', 'expired' => 'Kedaluwarsa',
        'new' => 'Baru', 'contacted' => 'Sudah dihubungi', 'qualified' => 'Memenuhi kriteria',
        'converted' => 'Berhasil dikonversi', 'closed' => 'Ditutup', 'submitted' => 'Dikirim',
        'reviewed' => 'Sudah dinilai', 'returned' => 'Dikembalikan', 'completed' => 'Selesai',
        'cancelled' => 'Dibatalkan'
    );
    return $names[$status] ?? ucfirst(str_replace('_', ' ', $status));
}

function assessment_type_name(string $type): string
{
    $names = array('quiz' => 'Kuis', 'assignment' => 'Tugas', 'evaluation' => 'Evaluasi');
    return $names[$type] ?? status_name($type);
}

function content_type_name(string $type): string
{
    $names = array('text' => 'Teks', 'link' => 'Tautan / Video', 'file' => 'Berkas');
    return $names[$type] ?? status_name($type);
}

function activity_name(string $action): string
{
    $names = array(
        'login' => 'Masuk ke sistem', 'logout' => 'Keluar dari sistem',
        'register' => 'Mendaftarkan akun', 'account_registered' => 'Mendaftarkan akun',
        'profile_updated' => 'Memperbarui profil', 'module_created' => 'Menambahkan modul',
        'password_changed' => 'Mengubah kata sandi', 'user_created' => 'Menambahkan pengguna',
        'course_created' => 'Menambahkan kursus', 'course_enrolled' => 'Mendaftar kursus',
        'material_created' => 'Menambahkan materi', 'material_completed' => 'Menyelesaikan materi',
        'assessment_created' => 'Menambahkan evaluasi', 'assessment_submitted' => 'Mengumpulkan evaluasi',
        'certificate_issued' => 'Menerbitkan sertifikat', 'voucher_created' => 'Membuat voucher',
        'voucher_redeemed' => 'Menukarkan voucher', 'plan_created' => 'Membuat paket layanan',
        'subscription_assigned' => 'Menetapkan paket layanan', 'lead_created' => 'Mencatat calon peserta',
        'record_updated' => 'Memperbarui status data', 'record_deleted' => 'Menghapus data',
        'enrolled' => 'Mengikuti kursus',
        'settings_saved' => 'Menyimpan pengaturan sistem',
        'google_login' => 'Masuk melalui Google', 'google_account_created' => 'Membuat akun melalui Google'
    );
    return $names[$action] ?? ucfirst(str_replace('_', ' ', $action));
}

function base64url_decode_strict(string $value): string
{
    $value = strtr($value, '-_', '+/');
    $padding = strlen($value) % 4;
    if ($padding !== 0) {
        $value .= str_repeat('=', 4 - $padding);
    }
    $decoded = base64_decode($value, true);
    if (!is_string($decoded)) {
        throw new RuntimeException('Token Google tidak valid. Silakan coba masuk kembali.');
    }
    return $decoded;
}

function google_fetch_signing_keys(bool $forceRefresh = false): array
{
    $cachePath = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'fkksm-google-signing-keys.json';
    if (!$forceRefresh && is_file($cachePath)) {
        $cached = json_decode((string)@file_get_contents($cachePath), true);
        if (is_array($cached) && !empty($cached['expires_at']) && $cached['expires_at'] > time() && !empty($cached['keys'])) {
            return $cached['keys'];
        }
    }

    if (!function_exists('curl_init')) {
        throw new RuntimeException('Server belum memiliki ekstensi cURL untuk masuk dengan Google.');
    }
    $curl = curl_init('https://www.googleapis.com/oauth2/v3/certs');
    curl_setopt_array($curl, array(
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CONNECTTIMEOUT => 5,
        CURLOPT_TIMEOUT => 10,
        CURLOPT_FOLLOWLOCATION => false,
        CURLOPT_SSL_VERIFYPEER => true,
        CURLOPT_SSL_VERIFYHOST => 2,
        CURLOPT_PROTOCOLS => CURLPROTO_HTTPS,
        CURLOPT_HTTPHEADER => array('Accept: application/json')
    ));
    $cacheControl = '';
    curl_setopt($curl, CURLOPT_HEADERFUNCTION, static function ($handle, string $line) use (&$cacheControl): int {
        if (stripos($line, 'Cache-Control:') === 0) {
            $cacheControl = trim(substr($line, strlen('Cache-Control:')));
        }
        return strlen($line);
    });
    $response = curl_exec($curl);
    $statusCode = (int)curl_getinfo($curl, CURLINFO_RESPONSE_CODE);
    curl_close($curl);
    if (!is_string($response) || $statusCode !== 200) {
        throw new RuntimeException('Tidak dapat memverifikasi akun Google saat ini. Periksa koneksi internet server, lalu coba lagi.');
    }
    $decoded = json_decode($response, true);
    if (!is_array($decoded) || empty($decoded['keys']) || !is_array($decoded['keys'])) {
        throw new RuntimeException('Kunci verifikasi Google tidak dapat dibaca. Silakan coba lagi nanti.');
    }
    $cacheSeconds = 3600;
    if (preg_match('/(?:^|,\s*)max-age=(\d+)/i', $cacheControl, $matches)) {
        $cacheSeconds = max(60, min(86400, (int)$matches[1]));
    }
    @file_put_contents($cachePath, json_encode(array('expires_at' => time() + $cacheSeconds, 'keys' => $decoded['keys'])), LOCK_EX);
    return $decoded['keys'];
}

function der_length(int $length): string
{
    if ($length < 128) {
        return chr($length);
    }
    $bytes = '';
    while ($length > 0) {
        $bytes = chr($length & 255) . $bytes;
        $length >>= 8;
    }
    return chr(128 | strlen($bytes)) . $bytes;
}

function der_value(int $tag, string $value): string
{
    return chr($tag) . der_length(strlen($value)) . $value;
}

function der_integer(string $value): string
{
    $value = ltrim($value, "\x00");
    if ($value === '') {
        $value = "\x00";
    } elseif ((ord($value[0]) & 128) !== 0) {
        $value = "\x00" . $value;
    }
    return der_value(2, $value);
}

function google_jwk_public_key(array $jwk): string
{
    if (empty($jwk['n']) || empty($jwk['e'])) {
        throw new RuntimeException('Kunci akun Google tidak lengkap.');
    }
    $rsaKey = der_value(48, der_integer(base64url_decode_strict($jwk['n'])) . der_integer(base64url_decode_strict($jwk['e'])));
    $algorithm = hex2bin('300d06092a864886f70d0101010500');
    if (!is_string($algorithm)) {
        throw new RuntimeException('Kunci akun Google tidak dapat digunakan.');
    }
    $publicKey = der_value(48, $algorithm . der_value(3, "\x00" . $rsaKey));
    return "-----BEGIN PUBLIC KEY-----\n" . chunk_split(base64_encode($publicKey), 64, "\n") . "-----END PUBLIC KEY-----\n";
}

function verify_google_id_token(string $token): array
{
    $clientId = trim((string)LMS_GOOGLE_CLIENT_ID);
    if ($clientId === '') {
        throw new RuntimeException('Masuk dengan Google belum dikonfigurasi. Tambahkan Web Client ID di lms/config.php.');
    }
    if (strlen($token) > 20000) {
        throw new RuntimeException('Token Google tidak valid. Silakan coba masuk kembali.');
    }
    $parts = explode('.', $token);
    if (count($parts) !== 3) {
        throw new RuntimeException('Token Google tidak valid. Silakan coba masuk kembali.');
    }
    $header = json_decode(base64url_decode_strict($parts[0]), true);
    $claims = json_decode(base64url_decode_strict($parts[1]), true);
    if (!is_array($header) || !is_array($claims) || ($header['alg'] ?? '') !== 'RS256' || empty($header['kid'])) {
        throw new RuntimeException('Token Google tidak valid. Silakan coba masuk kembali.');
    }

    $keys = google_fetch_signing_keys();
    $jwk = null;
    foreach ($keys as $key) {
        if (($key['kid'] ?? '') === $header['kid']) {
            $jwk = $key;
            break;
        }
    }
    if ($jwk === null) {
        foreach (google_fetch_signing_keys(true) as $key) {
            if (($key['kid'] ?? '') === $header['kid']) {
                $jwk = $key;
                break;
            }
        }
    }
    if ($jwk === null) {
        throw new RuntimeException('Token Google sudah tidak berlaku. Silakan coba masuk kembali.');
    }
    $publicKey = openssl_pkey_get_public(google_jwk_public_key($jwk));
    if ($publicKey === false || openssl_verify($parts[0] . '.' . $parts[1], base64url_decode_strict($parts[2]), $publicKey, OPENSSL_ALGO_SHA256) !== 1) {
        throw new RuntimeException('Token Google tidak dapat diverifikasi. Silakan coba masuk kembali.');
    }

    $now = time();
    $audience = $claims['aud'] ?? null;
    if (!in_array($claims['iss'] ?? '', array('accounts.google.com', 'https://accounts.google.com'), true)
        || $audience !== $clientId
        || (isset($claims['azp']) && $claims['azp'] !== $clientId)
        || !isset($claims['exp']) || (int)$claims['exp'] <= $now
        || (isset($claims['nbf']) && (int)$claims['nbf'] > $now + 60)
        || (isset($claims['iat']) && (int)$claims['iat'] > $now + 60)
        || empty($claims['sub'])) {
        throw new RuntimeException('Token Google sudah tidak berlaku atau tidak sesuai dengan aplikasi ini.');
    }
    $email = strtolower(trim((string)($claims['email'] ?? '')));
    $emailVerified = ($claims['email_verified'] ?? false) === true || ($claims['email_verified'] ?? '') === 'true';
    if (!$emailVerified || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new RuntimeException('Google tidak memberikan alamat email terverifikasi untuk akun ini.');
    }
    $domain = substr(strrchr($email, '@') ?: '', 1);
    $authoritativeEmail = $domain === 'gmail.com' || ($emailVerified && !empty($claims['hd']));
    return array(
        'sub' => (string)$claims['sub'],
        'email' => $email,
        'email_authoritative' => $authoritativeEmail,
        'full_name' => trim((string)($claims['name'] ?? '')) ?: strstr($email, '@', true),
    );
}

function google_user(PDO $pdo, array $identity): array
{
    $statement = $pdo->prepare('SELECT id, username, email, full_name, role, status, must_change_password, google_sub FROM users WHERE google_sub = ? LIMIT 1');
    $statement->execute(array($identity['sub']));
    $account = $statement->fetch();

    if (!$account) {
        $statement = $pdo->prepare('SELECT id, username, email, full_name, role, status, must_change_password, google_sub FROM users WHERE email = ? LIMIT 1');
        $statement->execute(array($identity['email']));
        $account = $statement->fetch();
        if ($account) {
            if (!$identity['email_authoritative']) {
                throw new RuntimeException('Email ini sudah terdaftar. Masuk dengan kata sandi akun tersebut untuk melindungi akun Anda.');
            }
            if (!empty($account['google_sub']) && $account['google_sub'] !== $identity['sub']) {
                throw new RuntimeException('Email ini sudah terhubung ke akun Google lain. Hubungi administrator.');
            }
            $pdo->prepare('UPDATE users SET google_sub = ? WHERE id = ?')->execute(array($identity['sub'], (int)$account['id']));
            $account['google_sub'] = $identity['sub'];
        } else {
            $username = 'google_' . substr(hash('sha256', $identity['sub']), 0, 32);
            $randomPassword = bin2hex(random_bytes(32));
            $statement = $pdo->prepare("INSERT INTO users (username, email, google_sub, full_name, password_hash, role, status, must_change_password) VALUES (?, ?, ?, ?, ?, 'student', 'active', 0)");
            $statement->execute(array($username, $identity['email'], $identity['sub'], $identity['full_name'], password_hash($randomPassword, PASSWORD_DEFAULT)));
            $accountId = (int)$pdo->lastInsertId();
            log_activity($pdo, $accountId, 'google_account_created', 'Membuat akun melalui Google');
            $statement = $pdo->prepare('SELECT id, username, email, full_name, role, status, must_change_password, google_sub FROM users WHERE id = ? LIMIT 1');
            $statement->execute(array($accountId));
            $account = $statement->fetch();
        }
    }
    if (!$account || $account['status'] !== 'active') {
        throw new RuntimeException('Akun LMS ini tidak aktif. Hubungi administrator untuk bantuan.');
    }
    return $account;
}

function log_activity(PDO $pdo, ?int $userId, string $action, string $details = ''): void
{
    $statement = $pdo->prepare('INSERT INTO activity_logs (user_id, action, details, ip_address) VALUES (?, ?, ?, ?)');
    $statement->execute(array($userId, $action, $details, substr((string)($_SERVER['REMOTE_ADDR'] ?? ''), 0, 45)));
}

function get_user(PDO $pdo, int $id): ?array
{
    $statement = $pdo->prepare("SELECT id, username, email, full_name, role, status, must_change_password, google_sub FROM users WHERE id = ? AND status = 'active' LIMIT 1");
    $statement->execute(array($id));
    $user = $statement->fetch();
    return $user ?: null;
}

function one_value(PDO $pdo, string $sql, array $params = array())
{
    $statement = $pdo->prepare($sql);
    $statement->execute($params);
    return $statement->fetchColumn();
}

function rows(PDO $pdo, string $sql, array $params = array()): array
{
    $statement = $pdo->prepare($sql);
    $statement->execute($params);
    return $statement->fetchAll();
}

function money($amount): string
{
    return 'Rp ' . number_format((float)$amount, 0, ',', '.');
}

function badge(string $label, string $tone = 'neutral'): string
{
    return '<span class="badge badge-' . h($tone) . '">' . h($label) . '</span>';
}

function require_admin_action(array $user): void
{
    if (!is_admin($user)) {
        throw new RuntimeException('Aksi ini hanya dapat dilakukan administrator.');
    }
}

function safe_slug(string $text): string
{
    $text = strtolower(trim($text));
    $text = preg_replace('/[^a-z0-9]+/', '-', $text) ?: 'kursus';
    return trim($text, '-') . '-' . substr(bin2hex(random_bytes(3)), 0, 6);
}

function ensure_record_management_columns(PDO $pdo): void
{
    foreach (array(
        array('course_modules', 'is_active', 'ALTER TABLE `course_modules` ADD COLUMN `is_active` TINYINT(1) NOT NULL DEFAULT 1'),
        array('certificates', 'is_revoked', 'ALTER TABLE `certificates` ADD COLUMN `is_revoked` TINYINT(1) NOT NULL DEFAULT 0')
    ) as $column) {
        $statement = $pdo->prepare('SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND COLUMN_NAME = ?');
        $statement->execute(array($column[0], $column[1]));
        if (!(int)$statement->fetchColumn()) {
            $pdo->exec($column[2]);
        }
    }
}

function admin_record_action_form(string $action, string $entity, int $id, string $label, string $page, bool $danger = false, string $confirm = ''): string
{
    $confirmation = $confirm !== '' ? ' onsubmit="return confirm(' . h((string)json_encode($confirm, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP)) . ')"' : '';
    $class = $danger ? 'button button-danger' : 'button button-soft';
    return '<form method="post" action="index.php?page=' . h($page) . '" class="row-action-form"' . $confirmation . '>' . csrf_input()
        . '<input type="hidden" name="action" value="' . h($action) . '">'
        . '<input type="hidden" name="entity" value="' . h($entity) . '">'
        . '<input type="hidden" name="record_id" value="' . h($id) . '">'
        . '<button class="' . $class . ' button-compact" type="submit">' . h($label) . '</button></form>';
}

function mutate_admin_record(PDO $pdo, array $user, string $action, string $entity, int $recordId): array
{
    require_admin_action($user);
    if ($recordId < 1) {
        throw new RuntimeException('Data yang dipilih tidak valid.');
    }

    $entities = array(
        'user' => array('table' => 'users', 'page' => 'users', 'label' => 'akun pengguna'),
        'course' => array('table' => 'courses', 'page' => 'courses', 'label' => 'kursus'),
        'module' => array('table' => 'course_modules', 'page' => 'materials', 'label' => 'modul'),
        'material' => array('table' => 'materials', 'page' => 'materials', 'label' => 'materi'),
        'assessment' => array('table' => 'assessments', 'page' => 'assessments', 'label' => 'evaluasi'),
        'certificate' => array('table' => 'certificates', 'page' => 'certificates', 'label' => 'sertifikat'),
        'voucher' => array('table' => 'vouchers', 'page' => 'vouchers', 'label' => 'voucher'),
        'plan' => array('table' => 'plans', 'page' => 'subscriptions', 'label' => 'paket layanan'),
        'subscription' => array('table' => 'subscriptions', 'page' => 'subscriptions', 'label' => 'langganan'),
        'enrollment' => array('table' => 'enrollments', 'page' => 'reports', 'label' => 'pendaftaran kursus'),
        'lead' => array('table' => 'leads', 'page' => 'leads', 'label' => 'calon peserta')
    );
    if (!isset($entities[$entity]) || !in_array($action, array('toggle_record', 'delete_record'), true)) {
        throw new RuntimeException('Aksi pengelolaan data tidak dikenali.');
    }

    $definition = $entities[$entity];
    $table = $definition['table'];
    $statement = $pdo->prepare('SELECT * FROM `' . $table . '` WHERE id = ? FOR UPDATE');
    $pdo->beginTransaction();
    try {
        $statement->execute(array($recordId));
        $record = $statement->fetch();
        if (!$record) {
            throw new RuntimeException('Data tidak ditemukan atau sudah dihapus.');
        }

        if ($action === 'toggle_record') {
            switch ($entity) {
                case 'user':
                    if ($record['role'] === 'super_admin') {
                        throw new RuntimeException('Akun Administrator Utama dilindungi dari perubahan status.');
                    }
                    if ((int)$record['id'] === (int)$user['id']) {
                        throw new RuntimeException('Anda tidak dapat menonaktifkan akun yang sedang digunakan.');
                    }
                    $next = $record['status'] === 'active' ? 'suspended' : 'active';
                    if ($record['status'] === 'active' && in_array($record['role'], array('admin', 'super_admin'), true)) {
                        $activeAdmins = (int)one_value($pdo, "SELECT COUNT(*) FROM users WHERE role IN ('admin','super_admin') AND status='active' AND id<>?", array($recordId));
                        if ($activeAdmins < 1) {
                            throw new RuntimeException('Sistem harus tetap memiliki setidaknya satu administrator aktif.');
                        }
                    }
                    $pdo->prepare('UPDATE users SET status = ? WHERE id = ?')->execute(array($next, $recordId));
                    break;
                case 'course':
                    $next = $record['status'] === 'published' ? 'archived' : 'published';
                    $pdo->prepare('UPDATE courses SET status = ? WHERE id = ?')->execute(array($next, $recordId));
                    break;
                case 'module':
                    $next = (int)$record['is_active'] ? 0 : 1;
                    $pdo->prepare('UPDATE course_modules SET is_active = ? WHERE id = ?')->execute(array($next, $recordId));
                    break;
                case 'material':
                    $next = (int)$record['is_published'] ? 0 : 1;
                    $pdo->prepare('UPDATE materials SET is_published = ? WHERE id = ?')->execute(array($next, $recordId));
                    break;
                case 'assessment':
                    $next = (int)$record['is_published'] ? 0 : 1;
                    $pdo->prepare('UPDATE assessments SET is_published = ? WHERE id = ?')->execute(array($next, $recordId));
                    break;
                case 'certificate':
                    $next = (int)$record['is_revoked'] ? 0 : 1;
                    $pdo->prepare('UPDATE certificates SET is_revoked = ? WHERE id = ?')->execute(array($next, $recordId));
                    break;
                case 'voucher':
                    if ($record['status'] !== 'active' && $record['expires_at'] && strtotime($record['expires_at']) <= time()) {
                        throw new RuntimeException('Voucher yang sudah kedaluwarsa tidak dapat diaktifkan kembali.');
                    }
                    $next = $record['status'] === 'active' ? 'paused' : 'active';
                    $pdo->prepare('UPDATE vouchers SET status = ? WHERE id = ?')->execute(array($next, $recordId));
                    break;
                case 'plan':
                    $next = $record['status'] === 'active' ? 'inactive' : 'active';
                    $pdo->prepare('UPDATE plans SET status = ? WHERE id = ?')->execute(array($next, $recordId));
                    break;
                case 'subscription':
                    if ($record['status'] === 'expired') {
                        throw new RuntimeException('Langganan yang sudah kedaluwarsa tidak dapat diaktifkan kembali.');
                    }
                    if ($record['status'] === 'cancelled' && $record['ends_at'] && strtotime($record['ends_at']) <= time()) {
                        throw new RuntimeException('Masa berlaku langganan sudah berakhir. Berikan akses baru melalui formulir langganan.');
                    }
                    $next = $record['status'] === 'cancelled' ? 'active' : 'cancelled';
                    $pdo->prepare('UPDATE subscriptions SET status = ? WHERE id = ?')->execute(array($next, $recordId));
                    break;
                case 'enrollment':
                    if ($record['status'] === 'completed') {
                        throw new RuntimeException('Pendaftaran yang sudah selesai tidak dapat dinonaktifkan.');
                    }
                    $next = $record['status'] === 'cancelled' ? 'active' : 'cancelled';
                    $pdo->prepare('UPDATE enrollments SET status = ? WHERE id = ?')->execute(array($next, $recordId));
                    break;
                case 'lead':
                    $next = $record['status'] === 'closed' ? 'new' : 'closed';
                    $pdo->prepare('UPDATE leads SET status = ? WHERE id = ?')->execute(array($next, $recordId));
                    break;
            }
            $label = $definition['label'];
            $message = 'Status ' . $label . ' berhasil diperbarui.';
            log_activity($pdo, (int)$user['id'], 'record_updated', $message . ' #' . $recordId);
            $pdo->commit();
            return array('page' => $definition['page'], 'message' => $message);
        }

        switch ($entity) {
            case 'user':
                if ((int)$record['id'] === (int)$user['id'] || $record['role'] === 'super_admin') {
                    throw new RuntimeException('Akun Administrator Utama atau akun yang sedang digunakan tidak dapat dihapus.');
                }
                if (in_array($record['role'], array('admin', 'super_admin'), true) && $record['status'] === 'active') {
                    $activeAdmins = (int)one_value($pdo, "SELECT COUNT(*) FROM users WHERE role IN ('admin','super_admin') AND status='active' AND id<>?", array($recordId));
                    if ($activeAdmins < 1) {
                        throw new RuntimeException('Sistem harus tetap memiliki setidaknya satu administrator aktif.');
                    }
                }
                $hasHistory = (int)one_value($pdo, 'SELECT (SELECT COUNT(*) FROM enrollments WHERE user_id=?) + (SELECT COUNT(*) FROM user_progress WHERE user_id=?) + (SELECT COUNT(*) FROM assessment_submissions WHERE user_id=?) + (SELECT COUNT(*) FROM certificates WHERE user_id=?) + (SELECT COUNT(*) FROM voucher_redemptions WHERE user_id=?) + (SELECT COUNT(*) FROM subscriptions WHERE user_id=?) + (SELECT COUNT(*) FROM notifications WHERE user_id=?)', array_fill(0, 7, $recordId));
                if ($hasHistory) {
                    throw new RuntimeException('Akun ini memiliki riwayat belajar atau transaksi. Nonaktifkan akun agar riwayat tetap tersimpan.');
                }
                break;
            case 'course':
                $hasHistory = (int)one_value($pdo, 'SELECT (SELECT COUNT(*) FROM course_modules WHERE course_id=?) + (SELECT COUNT(*) FROM materials WHERE course_id=?) + (SELECT COUNT(*) FROM enrollments WHERE course_id=?) + (SELECT COUNT(*) FROM assessments WHERE course_id=?) + (SELECT COUNT(*) FROM certificates WHERE course_id=?)', array_fill(0, 5, $recordId));
                if ($hasHistory) {
                    throw new RuntimeException('Kursus memiliki modul, materi, peserta, evaluasi, atau sertifikat. Arsipkan kursus agar riwayat tetap tersimpan.');
                }
                break;
            case 'module':
                if ((int)one_value($pdo, 'SELECT COUNT(*) FROM materials WHERE module_id=?', array($recordId))) {
                    throw new RuntimeException('Modul masih memiliki materi. Nonaktifkan modul agar susunan pembelajaran tetap tersimpan.');
                }
                break;
            case 'material':
                if ((int)one_value($pdo, 'SELECT COUNT(*) FROM user_progress WHERE material_id=?', array($recordId))) {
                    throw new RuntimeException('Materi memiliki catatan progres peserta. Sembunyikan materi agar riwayat belajar tetap tersimpan.');
                }
                break;
            case 'assessment':
                if ((int)one_value($pdo, 'SELECT COUNT(*) FROM assessment_submissions WHERE assessment_id=?', array($recordId))) {
                    throw new RuntimeException('Evaluasi memiliki jawaban peserta. Tarik publikasi evaluasi agar jawaban tetap tersimpan.');
                }
                break;
            case 'certificate':
                if (!(int)$record['is_revoked']) {
                    throw new RuntimeException('Cabut sertifikat terlebih dahulu sebelum menghapusnya.');
                }
                break;
            case 'voucher':
                if ((int)one_value($pdo, 'SELECT COUNT(*) FROM voucher_redemptions WHERE voucher_id=?', array($recordId))) {
                    throw new RuntimeException('Voucher sudah pernah ditukarkan. Jeda voucher agar riwayat penukaran tetap tersimpan.');
                }
                break;
            case 'plan':
                if ((int)one_value($pdo, 'SELECT COUNT(*) FROM subscriptions WHERE plan_id=?', array($recordId))) {
                    throw new RuntimeException('Paket memiliki riwayat langganan. Nonaktifkan paket agar riwayat tetap tersimpan.');
                }
                break;
            case 'subscription':
                if (in_array($record['status'], array('active', 'pending'), true)) {
                    throw new RuntimeException('Batalkan langganan terlebih dahulu sebelum menghapusnya.');
                }
                break;
            case 'enrollment':
                throw new RuntimeException('Riwayat pendaftaran disimpan untuk menjaga catatan belajar peserta. Nonaktifkan pendaftaran jika akses perlu dihentikan.');
        }

        $pdo->prepare('DELETE FROM `' . $table . '` WHERE id = ?')->execute(array($recordId));
        $message = ucfirst($definition['label']) . ' berhasil dihapus.';
        log_activity($pdo, (int)$user['id'], 'record_deleted', $message . ' #' . $recordId);
        $pdo->commit();
        return array('page' => $definition['page'], 'message' => $message);
    } catch (Throwable $exception) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        throw $exception;
    }
}

if ($pdo instanceof PDO) {
    try {
        ensure_record_management_columns($pdo);
    } catch (PDOException $exception) {
        $databaseError = 'Pembaruan struktur basis data gagal. Periksa izin ALTER TABLE pada pengguna basis data.';
        $pdo = null;
    }
}

$user = null;
if ($pdo instanceof PDO && !empty($_SESSION['lms_user_id'])) {
    $user = get_user($pdo, (int)$_SESSION['lms_user_id']);
    if (!$user) {
        unset($_SESSION['lms_user_id']);
    }
}

$postError = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $pdo instanceof PDO) {
    try {
        verify_csrf();
        $action = (string)($_POST['action'] ?? '');

        if ($action === 'login') {
            $attempts = (int)($_SESSION['lms_login_attempts'] ?? 0);
            if ($attempts >= 8) {
                flash('error', 'Terlalu banyak percobaan masuk. Tutup dan buka kembali peramban sebelum mencoba lagi.');
                go('login', array('retry' => 1));
            }
            $login = trim((string)($_POST['login'] ?? ''));
            $password = (string)($_POST['password'] ?? '');
            $statement = $pdo->prepare("SELECT id, password_hash, status FROM users WHERE username = ? OR email = ? LIMIT 1");
            $statement->execute(array($login, $login));
            $account = $statement->fetch();
            if (!$account || $account['status'] !== 'active' || !password_verify($password, $account['password_hash'])) {
                $_SESSION['lms_login_attempts'] = $attempts + 1;
                flash('error', 'Nama pengguna/email atau kata sandi tidak cocok. Silakan masukkan ulang.');
                go('login', array('retry' => 1));
            }
            session_regenerate_id(true);
            $_SESSION['lms_user_id'] = (int)$account['id'];
            $_SESSION['lms_login_attempts'] = 0;
            $pdo->prepare('UPDATE users SET last_login_at = NOW() WHERE id = ?')->execute(array((int)$account['id']));
            log_activity($pdo, (int)$account['id'], 'login', 'Masuk ke LMS');
            go('dashboard');
        }

        if ($action === 'google_login') {
            $identity = verify_google_id_token((string)($_POST['credential'] ?? ''));
            $account = google_user($pdo, $identity);
            session_regenerate_id(true);
            $_SESSION['lms_user_id'] = (int)$account['id'];
            $_SESSION['lms_login_attempts'] = 0;
            $pdo->prepare('UPDATE users SET last_login_at = NOW() WHERE id = ?')->execute(array((int)$account['id']));
            log_activity($pdo, (int)$account['id'], 'google_login', 'Masuk ke LMS dengan akun Google');
            go('dashboard');
        }

        if ($action === 'register') {
            $name = trim((string)($_POST['full_name'] ?? ''));
            $email = strtolower(trim((string)($_POST['email'] ?? '')));
            $username = trim((string)($_POST['username'] ?? ''));
            $password = (string)($_POST['password'] ?? '');
            $confirmation = (string)($_POST['password_confirmation'] ?? '');
            if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || !preg_match('/^[A-Za-z0-9._-]{3,60}$/', $username)) {
                throw new RuntimeException('Lengkapi nama, email yang valid, dan nama pengguna 3–60 karakter.');
            }
            if (strlen($password) < 10 || $password !== $confirmation) {
                throw new RuntimeException('Kata sandi minimal 10 karakter dan harus sama dengan konfirmasinya.');
            }
            $statement = $pdo->prepare("INSERT INTO users (username, email, full_name, password_hash, role, status) VALUES (?, ?, ?, ?, 'student', 'active')");
            $statement->execute(array($username, $email, $name, password_hash($password, PASSWORD_DEFAULT)));
            log_activity($pdo, (int)$pdo->lastInsertId(), 'register', 'Pendaftaran akun peserta');
            flash('success', 'Akun berhasil dibuat. Silakan masuk.');
            go('login');
        }

        if (!$user) {
            go('login');
        }

        $userId = (int)$user['id'];
        switch ($action) {
            case 'logout':
                log_activity($pdo, $userId, 'logout', 'Keluar dari LMS');
                $_SESSION = array();
                session_regenerate_id(true);
                flash('success', 'Anda sudah keluar dari LMS.');
                go('login');

            case 'change_password':
                $currentPassword = (string)($_POST['current_password'] ?? '');
                $newPassword = (string)($_POST['new_password'] ?? '');
                $confirmation = (string)($_POST['new_password_confirmation'] ?? '');
                $stored = rows($pdo, 'SELECT password_hash FROM users WHERE id = ?', array($userId));
                if (!$stored || (empty($user['google_sub']) && !password_verify($currentPassword, $stored[0]['password_hash']))) {
                    throw new RuntimeException('Kata sandi saat ini tidak cocok.');
                }
                if (strlen($newPassword) < 10 || $newPassword !== $confirmation) {
                    throw new RuntimeException('Kata sandi baru minimal 10 karakter dan harus sama dengan konfirmasinya.');
                }
                $pdo->prepare('UPDATE users SET password_hash = ?, must_change_password = 0 WHERE id = ?')->execute(array(password_hash($newPassword, PASSWORD_DEFAULT), $userId));
                log_activity($pdo, $userId, 'password_changed', 'Kata sandi diperbarui');
                flash('success', 'Kata sandi berhasil diperbarui.');
                go('dashboard');

            case 'create_user':
                require_admin_action($user);
                $name = trim((string)($_POST['full_name'] ?? ''));
                $username = trim((string)($_POST['username'] ?? ''));
                $email = strtolower(trim((string)($_POST['email'] ?? '')));
                $role = (string)($_POST['role'] ?? 'student');
                $temporaryPassword = (string)($_POST['temporary_password'] ?? '');
                if ($name === '' || !preg_match('/^[A-Za-z0-9._-]{3,60}$/', $username) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    throw new RuntimeException('Nama, nama pengguna, dan email harus diisi dengan benar.');
                }
                if (strlen($temporaryPassword) < 10 || !in_array($role, array('admin', 'instructor', 'student', 'company'), true)) {
                    throw new RuntimeException('Gunakan kata sandi sementara minimal 10 karakter dan peran yang tersedia.');
                }
                $statement = $pdo->prepare('INSERT INTO users (username, email, full_name, password_hash, role, status, must_change_password) VALUES (?, ?, ?, ?, ?, ?, 1)');
                $statement->execute(array($username, $email, $name, password_hash($temporaryPassword, PASSWORD_DEFAULT), $role, (string)($_POST['status'] ?? 'active')));
                log_activity($pdo, $userId, 'user_created', 'Membuat akun ' . $username);
                flash('success', 'Akun pengguna berhasil dibuat. Pengguna diminta mengganti kata sandi saat masuk.');
                go('users');

            case 'create_course':
                require_admin_action($user);
                $title = trim((string)($_POST['title'] ?? ''));
                if ($title === '') {
                    throw new RuntimeException('Nama kursus wajib diisi.');
                }
                $accessType = (string)($_POST['access_type'] ?? 'free');
                if (!in_array($accessType, array('free', 'paid'), true)) {
                    $accessType = 'free';
                }
                $status = (string)($_POST['status'] ?? 'draft');
                if (!in_array($status, array('draft', 'published'), true)) {
                    $status = 'draft';
                }
                $instructor = (int)($_POST['instructor_id'] ?? 0) ?: null;
                $statement = $pdo->prepare('INSERT INTO courses (title, slug, category, summary, description, instructor_id, duration_hours, access_type, price, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
                $statement->execute(array($title, safe_slug($title), trim((string)($_POST['category'] ?? '')) ?: null, trim((string)($_POST['summary'] ?? '')) ?: null, trim((string)($_POST['description'] ?? '')) ?: null, $instructor, (float)($_POST['duration_hours'] ?? 0) ?: null, $accessType, max(0, (float)($_POST['price'] ?? 0)), $status));
                log_activity($pdo, $userId, 'course_created', 'Membuat kursus ' . $title);
                flash('success', 'Kursus berhasil disimpan.');
                go('courses');

            case 'create_module':
                require_admin_action($user);
                $courseId = (int)($_POST['course_id'] ?? 0);
                $title = trim((string)($_POST['title'] ?? ''));
                if (!$courseId || $title === '') {
                    throw new RuntimeException('Pilih kursus dan isi nama modul.');
                }
                $pdo->prepare('INSERT INTO course_modules (course_id, title, summary, sort_order) VALUES (?, ?, ?, ?)')->execute(array($courseId, $title, trim((string)($_POST['summary'] ?? '')) ?: null, (int)($_POST['sort_order'] ?? 0)));
                log_activity($pdo, $userId, 'module_created', 'Membuat modul ' . $title);
                flash('success', 'Modul berhasil ditambahkan.');
                go('materials');

            case 'create_material':
                require_admin_action($user);
                $courseId = (int)($_POST['course_id'] ?? 0);
                $title = trim((string)($_POST['title'] ?? ''));
                $type = (string)($_POST['content_type'] ?? 'text');
                if (!$courseId || $title === '' || !in_array($type, array('text', 'link', 'file'), true)) {
                    throw new RuntimeException('Pilih kursus, isi judul, dan tentukan jenis materi.');
                }
                $moduleId = (int)($_POST['module_id'] ?? 0) ?: null;
                if ($moduleId && !(int)one_value($pdo, 'SELECT COUNT(*) FROM course_modules WHERE id=? AND course_id=? AND is_active=1', array($moduleId, $courseId))) {
                    throw new RuntimeException('Pilih modul aktif yang berada di dalam kursus tersebut.');
                }
                $published = isset($_POST['is_published']) ? 1 : 0;
                $contentUrl = trim((string)($_POST['content_url'] ?? ''));
                if (isset($_FILES['video_file']) && $_FILES['video_file']['error'] !== UPLOAD_ERR_NO_FILE) {
                    $video = $_FILES['video_file'];
                    $ext = strtolower(pathinfo($video['name'], PATHINFO_EXTENSION));
                    if ($video['error'] !== UPLOAD_ERR_OK || $video['size'] > 200 * 1024 * 1024 || !in_array($ext, array('mp4','mov'), true)) throw new RuntimeException('Unggah video MP4/MOV maksimal 200 MB.');
                    $dir = __DIR__ . '/uploads/videos';
                    if (!is_dir($dir) && !mkdir($dir, 0755, true)) throw new RuntimeException('Folder video gagal dibuat.');
                    $name = bin2hex(random_bytes(16)) . '.' . $ext;
                    if (!move_uploaded_file($video['tmp_name'], $dir . '/' . $name)) throw new RuntimeException('File video gagal disimpan.');
                    $contentUrl = 'uploads/videos/' . $name;
                    $type = 'file';
                }
                $pdo->prepare('INSERT INTO materials (course_id, module_id, title, content_type, content_text, content_url, sort_order, is_published) VALUES (?, ?, ?, ?, ?, ?, ?, ?)')->execute(array($courseId, $moduleId, $title, $type, trim((string)($_POST['content_text'] ?? '')) ?: null, ($contentUrl ?: null), (int)($_POST['sort_order'] ?? 0), $published));
                log_activity($pdo, $userId, 'material_created', 'Membuat materi ' . $title);
                flash('success', 'Materi berhasil disimpan.');
                go('materials');

            case 'enroll':
                $courseId = (int)($_POST['course_id'] ?? 0);
                $statement = $pdo->prepare("SELECT id, title, access_type, status FROM courses WHERE id = ? AND status = 'published'");
                $statement->execute(array($courseId));
                $course = $statement->fetch();
                if (!$course) {
                    throw new RuntimeException('Kursus tidak tersedia.');
                }
                if ($course['access_type'] === 'paid') {
                    throw new RuntimeException('Kursus berbayar memerlukan paket akses yang dikonfirmasi administrator.');
                }
                $pdo->prepare("INSERT INTO enrollments (user_id, course_id, status) VALUES (?, ?, 'active') ON DUPLICATE KEY UPDATE status = 'active'")->execute(array($userId, $courseId));
                log_activity($pdo, $userId, 'enrolled', 'Mengikuti kursus ' . $course['title']);
                flash('success', 'Anda berhasil mendaftar ke kursus.');
                go('materials');

            case 'complete_material':
                $materialId = (int)($_POST['material_id'] ?? 0);
                $allowed = one_value($pdo, "SELECT COUNT(*) FROM materials m INNER JOIN enrollments e ON e.course_id = m.course_id WHERE m.id = ? AND e.user_id = ? AND e.status = 'active'", array($materialId, $userId));
                if (!(int)$allowed) {
                    throw new RuntimeException('Materi tidak ditemukan dalam kursus yang Anda ikuti.');
                }
                $pdo->prepare('INSERT IGNORE INTO user_progress (user_id, material_id) VALUES (?, ?)')->execute(array($userId, $materialId));
                flash('success', 'Progres belajar diperbarui.');
                go('materials');

            case 'create_assessment':
                require_admin_action($user);
                $courseId = (int)($_POST['course_id'] ?? 0);
                $title = trim((string)($_POST['title'] ?? ''));
                $type = (string)($_POST['assessment_type'] ?? 'quiz');
                if (!$courseId || $title === '' || !in_array($type, array('quiz', 'assignment', 'evaluation'), true)) {
                    throw new RuntimeException('Pilih kursus, isi judul, dan tentukan jenis evaluasi.');
                }
                $dueAt = trim((string)($_POST['due_at'] ?? ''));
                $dueAt = $dueAt !== '' ? str_replace('T', ' ', $dueAt) . ':00' : null;
                $pdo->prepare('INSERT INTO assessments (course_id, title, assessment_type, instructions, is_optional, is_published, due_at) VALUES (?, ?, ?, ?, ?, ?, ?)')->execute(array($courseId, $title, $type, trim((string)($_POST['instructions'] ?? '')) ?: null, isset($_POST['is_optional']) ? 1 : 0, isset($_POST['is_published']) ? 1 : 0, $dueAt));
                log_activity($pdo, $userId, 'assessment_created', 'Membuat evaluasi ' . $title);
                flash('success', 'Evaluasi berhasil ditambahkan.');
                go('assessments');

            case 'submit_assessment':
                $assessmentId = (int)($_POST['assessment_id'] ?? 0);
                $response = trim((string)($_POST['response_text'] ?? ''));
                $allowed = one_value($pdo, "SELECT COUNT(*) FROM assessments a INNER JOIN enrollments e ON e.course_id = a.course_id WHERE a.id = ? AND a.is_published = 1 AND e.user_id = ? AND e.status = 'active'", array($assessmentId, $userId));
                if (!(int)$allowed || $response === '') {
                    throw new RuntimeException('Jawaban wajib diisi untuk evaluasi yang tersedia pada kursus Anda.');
                }
                $pdo->prepare("INSERT INTO assessment_submissions (assessment_id, user_id, response_text, status) VALUES (?, ?, ?, 'submitted')")->execute(array($assessmentId, $userId, $response));
                log_activity($pdo, $userId, 'assessment_submitted', 'Mengirim jawaban evaluasi #' . $assessmentId);
                flash('success', 'Jawaban berhasil dikirim.');
                go('assessments');

            case 'issue_certificate':
                require_admin_action($user);
                $studentId = (int)($_POST['user_id'] ?? 0);
                $courseId = (int)($_POST['course_id'] ?? 0);
                if (!$studentId || !$courseId) {
                    throw new RuntimeException('Pilih peserta dan kursus untuk menerbitkan sertifikat.');
                }
                $number = 'FKKSM-' . date('Y') . '-' . strtoupper(substr(bin2hex(random_bytes(5)), 0, 10));
                $pdo->prepare('INSERT INTO certificates (certificate_no, user_id, course_id, issued_by) VALUES (?, ?, ?, ?)')->execute(array($number, $studentId, $courseId, $userId));
                log_activity($pdo, $userId, 'certificate_issued', 'Menerbitkan sertifikat ' . $number);
                flash('success', 'Sertifikat berhasil diterbitkan.');
                go('certificates');

            case 'create_voucher':
                require_admin_action($user);
                $code = strtoupper(trim((string)($_POST['code'] ?? '')));
                $title = trim((string)($_POST['title'] ?? ''));
                $type = (string)($_POST['benefit_type'] ?? 'free_access');
                if (!preg_match('/^[A-Z0-9_-]{4,80}$/', $code) || $title === '' || !in_array($type, array('free_access', 'discount', 'program_access'), true)) {
                    throw new RuntimeException('Kode voucher 4–80 karakter dan judul harus diisi.');
                }
                $maxUses = (int)($_POST['max_uses'] ?? 0) ?: null;
                $expires = trim((string)($_POST['expires_at'] ?? ''));
                $course = (int)($_POST['course_id'] ?? 0) ?: null;
                $pdo->prepare('INSERT INTO vouchers (code, title, benefit_type, benefit_description, course_id, discount_value, max_uses, expires_at, status, created_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)')->execute(array($code, $title, $type, trim((string)($_POST['benefit_description'] ?? '')) ?: null, $course, max(0, (float)($_POST['discount_value'] ?? 0)), $maxUses, $expires ? str_replace('T', ' ', $expires) . ':00' : null, isset($_POST['is_active']) ? 'active' : 'paused', $userId));
                log_activity($pdo, $userId, 'voucher_created', 'Membuat voucher ' . $code);
                flash('success', 'Voucher berhasil disimpan.');
                go('vouchers');

            case 'redeem_voucher':
                $code = strtoupper(trim((string)($_POST['code'] ?? '')));
                $pdo->beginTransaction();
                $statement = $pdo->prepare('SELECT * FROM vouchers WHERE code = ? FOR UPDATE');
                $statement->execute(array($code));
                $voucher = $statement->fetch();
                if (!$voucher || $voucher['status'] !== 'active' || ($voucher['expires_at'] && strtotime($voucher['expires_at']) < time()) || ($voucher['starts_at'] && strtotime($voucher['starts_at']) > time())) {
                    $pdo->rollBack();
                    throw new RuntimeException('Kode voucher tidak tersedia atau sudah kedaluwarsa.');
                }
                $uses = (int)one_value($pdo, 'SELECT COUNT(*) FROM voucher_redemptions WHERE voucher_id = ?', array((int)$voucher['id']));
                if ($voucher['max_uses'] !== null && $uses >= (int)$voucher['max_uses']) {
                    $pdo->rollBack();
                    throw new RuntimeException('Kuota voucher sudah habis.');
                }
                $pdo->prepare('INSERT INTO voucher_redemptions (voucher_id, user_id) VALUES (?, ?)')->execute(array((int)$voucher['id'], $userId));
                if ($voucher['benefit_type'] === 'free_access' && $voucher['course_id']) {
                    $pdo->prepare("INSERT INTO enrollments (user_id, course_id, status) VALUES (?, ?, 'active') ON DUPLICATE KEY UPDATE status = 'active'")->execute(array($userId, (int)$voucher['course_id']));
                }
                $pdo->commit();
                log_activity($pdo, $userId, 'voucher_redeemed', 'Menukarkan voucher ' . $code);
                flash('success', 'Voucher berhasil ditukarkan.');
                go('vouchers');

            case 'create_plan':
                require_admin_action($user);
                $name = trim((string)($_POST['name'] ?? ''));
                if ($name === '') {
                    throw new RuntimeException('Nama paket wajib diisi.');
                }
                $pdo->prepare('INSERT INTO plans (name, description, price, duration_days, status) VALUES (?, ?, ?, ?, ?)')->execute(array($name, trim((string)($_POST['description'] ?? '')) ?: null, max(0, (float)($_POST['price'] ?? 0)), (int)($_POST['duration_days'] ?? 0) ?: null, isset($_POST['is_active']) ? 'active' : 'inactive'));
                log_activity($pdo, $userId, 'plan_created', 'Membuat paket ' . $name);
                flash('success', 'Paket akses berhasil disimpan.');
                go('subscriptions');

            case 'assign_subscription':
                require_admin_action($user);
                $studentId = (int)($_POST['user_id'] ?? 0);
                $planId = (int)($_POST['plan_id'] ?? 0);
                $days = (int)($_POST['duration_days'] ?? 0);
                if (!$studentId || !$planId || $days < 1) {
                    throw new RuntimeException('Pilih pengguna, paket, dan masa aktif minimal satu hari.');
                }
                $start = date('Y-m-d H:i:s');
                $end = date('Y-m-d H:i:s', strtotime('+' . $days . ' days'));
                $pdo->prepare("INSERT INTO subscriptions (user_id, plan_id, status, starts_at, ends_at, created_by) VALUES (?, ?, 'active', ?, ?, ?)")->execute(array($studentId, $planId, $start, $end, $userId));
                log_activity($pdo, $userId, 'subscription_assigned', 'Menetapkan paket #' . $planId . ' ke pengguna #' . $studentId);
                flash('success', 'Akses paket berhasil diberikan.');
                go('subscriptions');

            case 'create_lead':
                require_admin_action($user);
                $name = trim((string)($_POST['full_name'] ?? ''));
                if ($name === '') {
                    throw new RuntimeException('Nama calon peserta wajib diisi.');
                }
                $pdo->prepare('INSERT INTO leads (full_name, email, phone, source, interest, status, notes, created_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?)')->execute(array($name, trim((string)($_POST['email'] ?? '')) ?: null, trim((string)($_POST['phone'] ?? '')) ?: null, trim((string)($_POST['source'] ?? '')) ?: null, trim((string)($_POST['interest'] ?? '')) ?: null, 'new', trim((string)($_POST['notes'] ?? '')) ?: null, $userId));
                log_activity($pdo, $userId, 'lead_created', 'Mencatat calon peserta ' . $name);
                flash('success', 'Data calon peserta berhasil disimpan.');
                go('leads');

            case 'save_settings':
                require_admin_action($user);
                $settings = array(
                    'notification_channel' => in_array((string)($_POST['notification_channel'] ?? ''), array('in_app', 'email', 'other'), true) ? (string)$_POST['notification_channel'] : 'in_app',
                    'notification_events' => implode(',', array_intersect((array)($_POST['notification_events'] ?? array()), array('course_enrollment', 'assessment_submission', 'voucher_redemption', 'new_lead'))),
                    'security_session_minutes' => (string)max(15, min(240, (int)($_POST['security_session_minutes'] ?? 60))),
                    'security_password_min_length' => '10'
                );
                $statement = $pdo->prepare('INSERT INTO settings (setting_key, setting_value, updated_by) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value), updated_by = VALUES(updated_by)');
                foreach ($settings as $key => $value) {
                    $statement->execute(array($key, $value, $userId));
                }
                log_activity($pdo, $userId, 'settings_saved', 'Memperbarui pengaturan sistem');
                flash('success', 'Pengaturan sistem berhasil disimpan.');
                go('settings');

            case 'toggle_record':
            case 'delete_record':
                $result = mutate_admin_record($pdo, $user, $action, (string)($_POST['entity'] ?? ''), (int)($_POST['record_id'] ?? 0));
                flash('success', $result['message']);
                go($result['page']);

            default:
                throw new RuntimeException('Aksi tidak dikenali.');
        }
    } catch (Throwable $exception) {
        if ($pdo instanceof PDO && $pdo->inTransaction()) {
            $pdo->rollBack();
        }
        $postError = $exception instanceof RuntimeException
            ? $exception->getMessage()
            : 'Terjadi kesalahan saat menyimpan data. Periksa kembali isian, lalu coba lagi.';
    }
}

$page = (string)($_GET['page'] ?? ($user ? 'dashboard' : 'login'));
$allowedPages = array('login', 'register', 'dashboard', 'users', 'courses', 'materials', 'assessments', 'certificates', 'vouchers', 'subscriptions', 'leads', 'reports', 'settings', 'profile');
if (!in_array($page, $allowedPages, true)) {
    $page = 'dashboard';
}
if (!$user && !in_array($page, array('login', 'register'), true)) {
    go('login');
}
if ($user && in_array($page, array('login', 'register'), true)) {
    go('dashboard');
}
if ($user && $user['must_change_password'] && $page !== 'profile') {
    go('profile');
}
if ($user && !is_admin($user) && in_array($page, array('users', 'leads', 'reports', 'settings'), true)) {
    $page = 'dashboard';
}

function page_title(string $page, array $user): array
{
    $admin = is_admin($user);
    $titles = array(
        'dashboard' => array($admin ? 'Dasbor Administrator' : 'Ruang Belajar', 'Ringkasan kursus, pengguna, dan aktivitas pembelajaran.'),
        'users' => array('Manajemen Pengguna', 'Kelola profil, kategori akun, dan hak akses pengguna.'),
        'courses' => array($admin ? 'Kursus & Pembelajaran' : 'Katalog Kursus', 'Kelola kursus, program pelatihan, dan akses belajar.'),
        'materials' => array('Materi & Progres', 'Susun materi pembelajaran dan pantau progres peserta.'),
        'assessments' => array('Kuis, Tugas & Evaluasi', 'Kelola evaluasi, pengumpulan jawaban, dan umpan balik.'),
        'certificates' => array('Sertifikat', 'Terbitkan dan lihat sertifikat penyelesaian program.'),
        'vouchers' => array($admin ? 'Voucher & Penukaran' : 'Voucher', 'Kelola kode voucher atau tukarkan kode akses.'),
        'subscriptions' => array($admin ? 'Pengaturan Langganan' : 'Langganan', 'Atur paket, masa aktif, dan status akses.'),
        'leads' => array('Pengelolaan Calon Peserta', 'Catat calon peserta, sumber kontak, dan minat program.'),
        'reports' => array('Laporan & Aktivitas', 'Pantau pendaftaran, aktivitas, dan penggunaan layanan.'),
        'settings' => array('Pengaturan Sistem', 'Notifikasi, hak akses, keamanan akun, dan catatan aktivitas.'),
        'profile' => array('Profil Akun', 'Kelola keamanan akun dan kata sandi Anda.')
    );
    return $titles[$page] ?? $titles['dashboard'];
}

function nav_link(string $page, string $label, string $symbol, string $activePage): string
{
    $class = $page === $activePage ? 'nav-link active' : 'nav-link';
    return '<a class="' . $class . '" href="index.php?page=' . h($page) . '"><span class="nav-icon">' . h($symbol) . '</span><span>' . h($label) . '</span></a>';
}

function course_options(PDO $pdo): array
{
    return rows($pdo, 'SELECT id, title FROM courses ORDER BY title');
}

function user_options(PDO $pdo): array
{
    return rows($pdo, "SELECT id, full_name, username FROM users WHERE role IN ('student','instructor','company') AND status = 'active' ORDER BY full_name");
}

function render_auth(string $page, string $databaseError, string $postError): void
{
    $message = take_flash();
    $clearLoginFields = $page === 'login' && isset($_GET['retry']);
    $googleLoginEnabled = trim((string)LMS_GOOGLE_CLIENT_ID) !== '';
    ?>
    <!doctype html>
    <html lang="id">
    <head>
      <meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
      <title>FKKSM LMS | Masuk</title><link rel="stylesheet" href="assets/lms.css?v=10">
      <?php if ($googleLoginEnabled): ?><script src="https://accounts.google.com/gsi/client" async></script><?php endif; ?>
    </head>
    <body class="auth-body">
      <main class="auth-layout">
        <section class="auth-brand">
          <a class="brand-lockup" href="../index.html"><img class="brand-logo" src="../logo-transparent.png" alt="Logo FKKSM"><span class="brand-copy"><span class="brand-name">FKKSM MM2100</span><span class="brand-subtitle">SISTEM MANAJEMEN PEMBELAJARAN</span></span></a>
          <div class="auth-pitch"><p class="eyebrow">BELAJAR · BERKEMBANG · TERHUBUNG</p><h1>Semua informasi,<br>dalam satu tempat.</h1><p>Ruang belajar dan administrasi program FKKSM MM2100.</p></div>
          <small class="auth-copy">© <?= date('Y') ?> FKKSM</small>
        </section>
        <section class="auth-panel">
          <div class="auth-card">
            <p class="eyebrow">1. PENDAFTARAN &amp; PENGELOLAAN AKUN</p>
            <p class="auth-welcome">SELAMAT DATANG</p>
            <h2><?= $page === 'register' ? 'Buat Akun Baru' : 'Masuk Akun Anda' ?></h2>
            <p class="muted"><?= $page === 'register' ? 'Daftar untuk mengikuti kegiatan pembelajaran dan pelatihan.' : 'Masuk untuk mengakses pembelajaran dan data akun.' ?></p>
            <?php if ($databaseError !== ''): ?><div class="notice notice-warning"><strong>Basis data belum tersambung</strong><p><?= h($databaseError) ?></p><p>Buka <code>http://localhost/phpmyadmin</code> dan impor file <code>lms/database/schema.sql</code>.</p></div><?php endif; ?>
            <?php if ($postError !== ''): ?><div class="notice notice-error"><?= h($postError) ?></div><?php endif; ?>
            <?php if ($message): ?><div class="notice notice-<?= h($message['type']) ?>"><?= h($message['message']) ?></div><?php endif; ?>
            <?php if ($page === 'register'): ?>
              <form method="post" class="form-stack">
                <?= csrf_input() ?><input type="hidden" name="action" value="register">
                <label>Nama lengkap<input name="full_name" required autocomplete="name"></label>
                <label>Nama pengguna<input name="username" required minlength="3" maxlength="60" autocomplete="username"></label>
                <label>Alamat email<input name="email" type="email" required autocomplete="email"></label>
                <label>Kata sandi<input name="password" type="password" required minlength="10" autocomplete="new-password"></label>
                <label>Konfirmasi kata sandi<input name="password_confirmation" type="password" required minlength="10" autocomplete="new-password"></label>
                <button class="button button-primary button-wide" type="submit">Daftar sekarang</button>
              </form>
              <p class="auth-switch">Sudah punya akun? <a href="index.php?page=login">Masuk di sini</a></p>
            <?php else: ?>
              <form method="post" class="form-stack" autocomplete="<?= $clearLoginFields ? 'off' : 'on' ?>" data-clear-login="<?= $clearLoginFields ? 'true' : 'false' ?>">
                <?= csrf_input() ?><input type="hidden" name="action" value="login">
                <label>Nama pengguna atau email<input name="login" required autocomplete="<?= $clearLoginFields ? 'off' : 'username' ?>"></label>
                <label>Kata sandi<input name="password" type="password" required autocomplete="<?= $clearLoginFields ? 'new-password' : 'current-password' ?>"></label>
                <button class="button button-primary button-wide" type="submit">Masuk sekarang</button>
              </form>
              <p class="auth-switch">Belum punya akun? <a href="index.php?page=register">Daftar di sini</a></p>
            <?php endif; ?>
            <?php if ($googleLoginEnabled): ?>
              <div class="auth-divider"><span>atau <?= $page === 'register' ? 'daftar' : 'masuk' ?> dengan</span></div>
              <div id="g_id_onload" data-client_id="<?= h(LMS_GOOGLE_CLIENT_ID) ?>" data-callback="handleGoogleCredential" data-auto_prompt="false"></div>
              <div class="g_id_signin" data-type="standard" data-shape="rectangular" data-theme="filled_blue" data-text="<?= $page === 'register' ? 'signup_with' : 'signin_with' ?>" data-size="large" data-logo_alignment="left"></div>
              <form id="googleLoginForm" class="google-login-post" method="post" action="index.php?page=login"><?= csrf_input() ?><input type="hidden" name="action" value="google_login"><input type="hidden" id="googleCredential" name="credential"></form>
              <script>
                function handleGoogleCredential(response) {
                  if (!response || !response.credential) return;
                  var credentialField = document.getElementById('googleCredential');
                  var loginForm = document.getElementById('googleLoginForm');
                  credentialField.value = response.credential;
                  loginForm.submit();
                }
              </script>
            <?php endif; ?>
            <p class="auth-note">Kategori akun dan hak akses mengikuti pengaturan administrator.</p>
          </div>
        </section>
      </main>
      <script src="assets/lms.js?v=3" defer></script>
    </body>
    </html>
    <?php
}

function render_content(string $page, array $user, PDO $pdo): void
{
    $admin = is_admin($user);
    $userId = (int)$user['id'];
    switch ($page) {
        case 'dashboard':
            if ($admin) {
                $stats = array(
                    array('Pengguna aktif', one_value($pdo, "SELECT COUNT(*) FROM users WHERE status = 'active'"), '♙', 'tone-purple'),
                    array('Kursus terbit', one_value($pdo, "SELECT COUNT(*) FROM courses WHERE status = 'published'"), '▤', 'tone-teal'),
                    array('Voucher aktif', one_value($pdo, "SELECT COUNT(*) FROM vouchers WHERE status = 'active'"), '▣', 'tone-purple'),
                    array('Calon peserta', one_value($pdo, 'SELECT COUNT(*) FROM leads'), '◷', 'tone-teal')
                );
                $recentCourses = rows($pdo, 'SELECT c.title, c.status, c.access_type, c.created_at, u.full_name AS instructor FROM courses c LEFT JOIN users u ON u.id = c.instructor_id ORDER BY c.created_at DESC LIMIT 5');
                ?>
                <section class="welcome-banner"><div><h2>Dasbor Administrator</h2><p>Pengelolaan kursus, pengguna, voucher, dan langganan.</p></div><span class="banner-mark">▦</span></section>
                <div class="stats-grid"><?php foreach ($stats as $stat): ?><article class="stat-card"><span class="stat-icon <?= h($stat[3]) ?>"><?= h($stat[2]) ?></span><span class="stat-label"><?= h($stat[0]) ?></span><strong><?= h($stat[1]) ?></strong></article><?php endforeach; ?></div>
                <div class="dashboard-grid"><section class="panel"><div class="panel-head"><div><h3>Pengelolaan kursus</h3><p class="muted">Kursus dan program yang baru dicatat.</p></div><a class="button button-soft" href="index.php?page=courses">Kelola kursus</a></div>
                  <?php if (!$recentCourses): ?><div class="empty-state"><strong>Belum ada kursus</strong><span>Tambahkan kursus pertama untuk mulai mengelola pembelajaran.</span></div><?php else: ?><div class="table-wrap"><table><thead><tr><th>Kursus / program</th><th>Instruktur</th><th>Akses</th><th>Status</th></tr></thead><tbody><?php foreach ($recentCourses as $course): ?><tr><td><?= h($course['title']) ?></td><td><?= h($course['instructor'] ?: 'Belum ditentukan') ?></td><td><?= h($course['access_type'] === 'free' ? 'Gratis' : 'Berbayar') ?></td><td><?= badge($course['status'] === 'published' ? 'Terbit' : 'Draf', $course['status'] === 'published' ? 'success' : 'neutral') ?></td></tr><?php endforeach; ?></tbody></table></div><?php endif; ?>
                </section><section class="panel"><div class="panel-head"><div><h3>Akses cepat</h3><p class="muted">Bagian administrasi LMS.</p></div></div><div class="quick-grid"><?php foreach (array(array('users','Pengguna','♙'),array('courses','Kursus','▤'),array('vouchers','Voucher','▣'),array('settings','Pengaturan','⚙')) as $item): ?><a class="quick-link" href="index.php?page=<?= h($item[0]) ?>"><span><?= h($item[2]) ?></span><?= h($item[1]) ?><b>→</b></a><?php endforeach; ?></div></section></div>
                <?php
            } else {
                $myCourses = rows($pdo, "SELECT c.id,c.title,c.summary,e.status FROM enrollments e JOIN courses c ON c.id=e.course_id WHERE e.user_id=? AND e.status IN ('active','completed') ORDER BY e.enrolled_at DESC LIMIT 6", array($userId));
                $available = rows($pdo, "SELECT c.id,c.title,c.summary,c.access_type,c.price FROM courses c WHERE c.status='published' AND NOT EXISTS (SELECT 1 FROM enrollments e WHERE e.course_id=c.id AND e.user_id=?) ORDER BY c.created_at DESC LIMIT 6", array($userId));
                ?>
                <section class="welcome-banner"><div><h2>Selamat belajar, <?= h($user['full_name']) ?></h2><p>Pilih kursus, pelajari materi, dan pantau progres Anda.</p></div><span class="banner-mark">▤</span></section>
                <div class="dashboard-grid"><section class="panel"><div class="panel-head"><div><h3>Kursus saya</h3><p class="muted">Kursus yang sedang Anda ikuti.</p></div><a class="text-link" href="index.php?page=materials">Lihat materi →</a></div><?php if (!$myCourses): ?><div class="empty-state"><strong>Belum ada kursus terdaftar</strong><span>Jelajahi katalog dan pilih kursus yang tersedia.</span></div><?php else: ?><div class="course-grid"><?php foreach ($myCourses as $course): ?><article class="course-card"><div class="course-card-top"><span class="course-icon">▤</span><?= badge($course['status'] === 'completed' ? 'Selesai' : 'Aktif', $course['status'] === 'completed' ? 'success' : 'info') ?></div><h3><?= h($course['title']) ?></h3><p><?= h($course['summary'] ?: 'Materi pembelajaran tersedia di dalam kursus ini.') ?></p><a class="button button-soft" href="index.php?page=materials">Buka materi</a></article><?php endforeach; ?></div><?php endif; ?></section>
                <section class="panel"><div class="panel-head"><div><h3>Katalog kursus</h3><p class="muted">Program pelatihan yang tersedia.</p></div><a class="text-link" href="index.php?page=courses">Lihat semua →</a></div><?php if (!$available): ?><div class="empty-state"><strong>Belum ada kursus baru</strong><span>Kursus yang tersedia akan ditampilkan di sini.</span></div><?php else: ?><div class="compact-list"><?php foreach ($available as $course): ?><div class="compact-row"><span><strong><?= h($course['title']) ?></strong><small><?= h($course['summary'] ?: 'Program FKKSM') ?></small></span><a class="text-link" href="index.php?page=courses">Lihat</a></div><?php endforeach; ?></div><?php endif; ?></section></div>
                <?php
            }
            break;

        case 'users':
            require_admin_action($user);
            $users = rows($pdo, 'SELECT id,username,email,full_name,role,status,created_at FROM users ORDER BY created_at DESC LIMIT 100');
            ?>
            <div class="content-grid two-col"><section class="panel"><div class="panel-head"><div><h3>Tambah pengguna</h3><p class="muted">Pengguna baru diminta mengganti kata sandi sementara.</p></div></div>
              <form method="post" class="form-grid"><?= csrf_input() ?><input type="hidden" name="action" value="create_user"><label>Nama lengkap<input name="full_name" required></label><label>Nama pengguna<input name="username" required minlength="3" maxlength="60"></label><label>Alamat email<input name="email" type="email" required></label><label>Peran<select name="role"><option value="student">Peserta</option><option value="instructor">Instruktur</option><option value="company">Perusahaan</option><option value="admin">Administrator</option></select></label><label>Kata sandi sementara<input name="temporary_password" type="password" required minlength="10"></label><label>Status<select name="status"><option value="active">Aktif</option><option value="pending">Menunggu</option></select></label><button class="button button-primary" type="submit">＋ Tambah pengguna</button></form>
            </section><section class="panel"><div class="panel-head"><div><h3>Hak akses pengguna</h3><p class="muted">Kategori akun dan peran tersimpan di basis data.</p></div></div><div class="role-list"><div><b>Administrator Utama</b><span>Akses penuh ke seluruh pengaturan sistem.</span></div><div><b>Administrator</b><span>Mengelola pengguna, kursus, laporan, dan pengaturan.</span></div><div><b>Instruktur</b><span>Mendukung pengelolaan materi dan evaluasi.</span></div><div><b>Peserta</b><span>Mengikuti kursus, materi, evaluasi, dan sertifikat.</span></div></div></section></div>
            <section class="panel"><div class="panel-head"><div><h3>Data pengguna</h3><p class="muted">Nonaktifkan akun atau hapus akun yang belum memiliki riwayat.</p></div></div><div class="table-wrap"><table><thead><tr><th>Pengguna</th><th>Alamat email</th><th>Peran</th><th>Status</th><th>Terdaftar</th><th>Aksi</th></tr></thead><tbody><?php foreach ($users as $row): ?><tr><td><strong><?= h($row['full_name']) ?></strong><small class="table-subtitle">@<?= h($row['username']) ?></small></td><td><?= h($row['email']) ?></td><td><?= h(role_name($row['role'])) ?></td><td><?= badge($row['status'] === 'active' ? 'Aktif' : status_name($row['status']), $row['status'] === 'active' ? 'success' : 'warning') ?></td><td><?= h(date('d M Y', strtotime($row['created_at']))) ?></td><td><div class="row-actions"><?php if ($row['role'] !== 'super_admin' && (int)$row['id'] !== $userId): ?><?= admin_record_action_form('toggle_record', 'user', (int)$row['id'], $row['status'] === 'active' ? 'Nonaktifkan' : 'Aktifkan', 'users') ?><?= admin_record_action_form('delete_record', 'user', (int)$row['id'], 'Hapus', 'users', true, 'Hapus akun ini? Penghapusan ditolak jika akun memiliki riwayat.') ?><?php else: ?><span class="muted">Akun dilindungi</span><?php endif; ?></div></td></tr><?php endforeach; ?></tbody></table></div></section>
            <?php
            break;

        case 'courses':
            if ($admin) {
                $courses = rows($pdo, 'SELECT c.*,u.full_name AS instructor,(SELECT COUNT(*) FROM enrollments e WHERE e.course_id=c.id) AS enrolled FROM courses c LEFT JOIN users u ON u.id=c.instructor_id ORDER BY c.created_at DESC');
                $instructors = rows($pdo, "SELECT id,full_name FROM users WHERE role IN ('instructor','admin','super_admin') AND status='active' ORDER BY full_name");
                ?>
                <section class="panel"><div class="panel-head"><div><h3>Tambah kursus</h3><p class="muted">Isi katalog kursus dan tetapkan status publikasi.</p></div></div>
                  <form method="post" class="form-grid course-create-form"><?= csrf_input() ?><input type="hidden" name="action" value="create_course"><label class="course-main-field">Nama kursus<input name="title" required placeholder="Contoh: Dasar Keselamatan Kerja"></label><label class="course-main-field">Ringkasan singkat<input name="summary" maxlength="500" placeholder="Apa yang akan dipelajari peserta?"></label><details class="course-options"><summary>Pengaturan lainnya <span>(opsional)</span></summary><div class="course-options-grid"><label>Kategori<input name="category" placeholder="Contoh: Kepemimpinan"></label><label>Instruktur<select name="instructor_id"><option value="">Belum ditentukan</option><?php foreach ($instructors as $item): ?><option value="<?= h($item['id']) ?>"><?= h($item['full_name']) ?></option><?php endforeach; ?></select></label><label>Durasi (jam)<input name="duration_hours" type="number" min="0" step="0.5"></label><label>Jenis akses<select name="access_type"><option value="free">Gratis</option><option value="paid">Berbayar</option></select></label><label>Harga (Rp)<input name="price" type="number" min="0" step="1000" value="0"></label><label>Status<select name="status"><option value="draft">Draf</option><option value="published">Terbit</option></select></label><label class="span-two">Deskripsi<textarea name="description" rows="3" placeholder="Tambahkan penjelasan lengkap bila diperlukan"></textarea></label></div></details><button class="button button-primary" type="submit">＋ Simpan kursus</button></form>
                </section>
                <section class="panel"><div class="panel-head"><div><h3>Daftar kursus</h3><p class="muted">Arsipkan atau hapus kursus yang belum memiliki data terkait.</p></div></div><?php if (!$courses): ?><div class="empty-state"><strong>Belum ada kursus</strong><span>Tambahkan data kursus melalui formulir di atas.</span></div><?php else: ?><div class="table-wrap"><table><thead><tr><th>Kursus / program</th><th>Instruktur</th><th>Durasi</th><th>Akses</th><th>Peserta</th><th>Status</th><th>Aksi</th></tr></thead><tbody><?php foreach ($courses as $course): ?><tr><td><strong><?= h($course['title']) ?></strong><small class="table-subtitle"><?= h($course['category'] ?: 'Tanpa kategori') ?></small></td><td><?= h($course['instructor'] ?: 'Belum ditentukan') ?></td><td><?= h($course['duration_hours'] ? $course['duration_hours'] . ' jam' : '—') ?></td><td><?= h($course['access_type'] === 'free' ? 'Gratis' : money($course['price'])) ?></td><td><?= h($course['enrolled']) ?></td><td><?= badge($course['status'] === 'published' ? 'Terbit' : status_name($course['status']), $course['status'] === 'published' ? 'success' : 'neutral') ?></td><td><div class="row-actions"><?= admin_record_action_form('toggle_record', 'course', (int)$course['id'], $course['status'] === 'published' ? 'Arsipkan' : 'Terbitkan', 'courses') ?><?= admin_record_action_form('delete_record', 'course', (int)$course['id'], 'Hapus', 'courses', true, 'Hapus kursus ini? Penghapusan ditolak jika kursus memiliki data belajar.') ?></div></td></tr><?php endforeach; ?></tbody></table></div><?php endif; ?></section>
                <?php
            } else {
                $courses = rows($pdo, "SELECT c.*,u.full_name AS instructor, EXISTS(SELECT 1 FROM enrollments e WHERE e.course_id=c.id AND e.user_id=?) AS enrolled FROM courses c LEFT JOIN users u ON u.id=c.instructor_id WHERE c.status='published' ORDER BY c.created_at DESC", array($userId));
                ?>
                <section class="panel"><div class="panel-head"><div><h3>Katalog kursus dan program pelatihan</h3><p class="muted">Pilih kursus yang sesuai dengan kebutuhan belajar Anda.</p></div><span class="search-mini">⌕ <input id="courseSearch" placeholder="Cari kursus atau program..."></span></div>
                  <?php if (!$courses): ?><div class="empty-state"><strong>Katalog belum tersedia</strong><span>Program kursus yang sudah diterbitkan akan tampil di sini.</span></div><?php else: ?><div class="course-grid" id="courseGrid"><?php foreach ($courses as $course): ?><article class="course-card searchable-card"><div class="course-card-top"><span class="course-icon">▤</span><?= badge($course['access_type'] === 'free' ? 'Akses gratis' : 'Berbayar', $course['access_type'] === 'free' ? 'success' : 'warning') ?></div><h3><?= h($course['title']) ?></h3><p><?= h($course['summary'] ?: 'Program pembelajaran FKKSM MM2100.') ?></p><div class="course-meta"><span><?= h($course['instructor'] ?: 'Instruktur menyusul') ?></span><span><?= h($course['duration_hours'] ? $course['duration_hours'] . ' jam' : 'Durasi fleksibel') ?></span></div><?php if ($course['enrolled']): ?><a class="button button-soft" href="index.php?page=materials">Buka materi</a><?php elseif ($course['access_type'] === 'free'): ?><form method="post"><?= csrf_input() ?><input type="hidden" name="action" value="enroll"><input type="hidden" name="course_id" value="<?= h($course['id']) ?>"><button class="button button-primary" type="submit">Ikuti kursus</button></form><?php else: ?><span class="button button-disabled">Hubungi administrator</span><?php endif; ?></article><?php endforeach; ?></div><?php endif; ?>
                </section>
                <?php
            }
            break;

        case 'materials':
            if ($admin) {
                $courses = course_options($pdo);
                $modules = rows($pdo, 'SELECT m.id,m.title,m.is_active,m.course_id,c.title AS course_title,(SELECT COUNT(*) FROM materials x WHERE x.module_id=m.id) AS material_count FROM course_modules m JOIN courses c ON c.id=m.course_id ORDER BY c.title,m.sort_order');
                $materials = rows($pdo, 'SELECT m.*,c.title AS course_title,cm.title AS module_title,(SELECT COUNT(*) FROM user_progress p WHERE p.material_id=m.id) AS progress_count FROM materials m JOIN courses c ON c.id=m.course_id LEFT JOIN course_modules cm ON cm.id=m.module_id ORDER BY c.title,m.sort_order,m.created_at DESC LIMIT 100');
                ?>
                <div class="content-grid two-col"><section class="panel"><div class="panel-head"><div><h3>Tambah modul</h3><p class="muted">Kelompokkan materi di dalam kursus.</p></div></div><form method="post" class="form-stack"><?= csrf_input() ?><input type="hidden" name="action" value="create_module"><label>Kursus<select name="course_id" required><option value="">Pilih kursus</option><?php foreach ($courses as $item): ?><option value="<?= h($item['id']) ?>"><?= h($item['title']) ?></option><?php endforeach; ?></select></label><label>Nama modul<input name="title" required></label><label>Ringkasan<textarea name="summary" rows="2"></textarea></label><label>Urutan<input name="sort_order" type="number" min="0" value="0"></label><button class="button button-primary">＋ Tambah modul</button></form></section>
                  <section class="panel"><div class="panel-head"><div><h3>Tambah materi</h3><p class="muted">Tambahkan teks pembelajaran dan tautan video YouTube.</p></div></div><form method="post" enctype="multipart/form-data" class="form-stack"><?= csrf_input() ?><input type="hidden" name="action" value="create_material"><label>Kursus<select name="course_id" required><option value="">Pilih kursus</option><?php foreach ($courses as $item): ?><option value="<?= h($item['id']) ?>"><?= h($item['title']) ?></option><?php endforeach; ?></select></label><label>Modul<select name="module_id"><option value="">Tanpa modul</option><?php foreach ($modules as $item): ?><?php if ($item['is_active']): ?><option value="<?= h($item['id']) ?>"><?= h($item['course_title'] . ' — ' . $item['title']) ?></option><?php endif; ?><?php endforeach; ?></select></label><label>Judul materi<input name="title" required></label><label>Jenis konten<select name="content_type"><option value="text">Teks</option><option value="link">Tautan / Video YouTube</option><option value="file">Berkas (tautan)</option></select></label><label>Isi materi<textarea name="content_text" rows="3"></textarea></label><label>URL materi atau video YouTube<input name="content_url" type="url" placeholder="https://www.youtube.com/watch?v=..."></label><label>Unggah video MP4/MOV (maks. 200 MB)<input type="file" name="video_file" accept=".mp4,.mov,video/mp4,video/quicktime"></label><label>Urutan<input name="sort_order" type="number" min="0" value="0"></label><label class="check-row"><input type="checkbox" name="is_published" value="1"> Terbitkan untuk peserta</label><button class="button button-primary">＋ Simpan materi</button></form></section></div>
                <section class="panel"><div class="panel-head"><div><h3>Daftar modul</h3><p class="muted">Nonaktifkan modul untuk menyembunyikan semua materi di dalamnya.</p></div></div><?php if (!$modules): ?><div class="empty-state">Belum ada modul.</div><?php else: ?><div class="table-wrap"><table><thead><tr><th>Modul</th><th>Kursus</th><th>Materi</th><th>Status</th><th>Aksi</th></tr></thead><tbody><?php foreach ($modules as $module): ?><tr><td><?= h($module['title']) ?></td><td><?= h($module['course_title']) ?></td><td><?= h($module['material_count']) ?></td><td><?= badge($module['is_active'] ? 'Aktif' : 'Nonaktif', $module['is_active'] ? 'success' : 'neutral') ?></td><td><div class="row-actions"><?= admin_record_action_form('toggle_record', 'module', (int)$module['id'], $module['is_active'] ? 'Nonaktifkan' : 'Aktifkan', 'materials') ?><?= admin_record_action_form('delete_record', 'module', (int)$module['id'], 'Hapus', 'materials', true, 'Hapus modul ini? Penghapusan ditolak jika modul masih memiliki materi.') ?></div></td></tr><?php endforeach; ?></tbody></table></div><?php endif; ?></section>
                <section class="panel"><div class="panel-head"><div><h3>Daftar materi</h3><p class="muted">Sembunyikan materi dari peserta atau hapus jika belum ada progres.</p></div></div><?php if (!$materials): ?><div class="empty-state"><strong>Belum ada materi</strong><span>Tambahkan kursus, modul, dan materi untuk mulai menyusun pembelajaran.</span></div><?php else: ?><div class="table-wrap"><table><thead><tr><th>Materi</th><th>Isi Materi</th><th>Kursus</th><th>Modul</th><th>Jenis</th><th>Status</th><th>Aksi</th></tr></thead><tbody><?php foreach ($materials as $item): ?><tr><td><strong><?= h($item['title']) ?></strong></td><td><?php if (!empty($item['content_text'])): ?><div><?= nl2br(h($item['content_text'])) ?></div><?php endif; ?><?php if (!empty($item['content_url'])): ?><?php $adminExt = strtolower(pathinfo((string)parse_url($item['content_url'], PHP_URL_PATH), PATHINFO_EXTENSION)); ?><?php if (in_array($adminExt, array('mp4','mov'), true)): ?><video controls preload="metadata" style="display:block;width:min(420px,100%);max-height:240px;margin-top:8px"><source src="<?= h($item['content_url']) ?>"></video><?php else: ?><a href="<?= h($item['content_url']) ?>" target="_blank" rel="noopener noreferrer">Buka materi ↗</a><?php endif; ?><?php endif; ?><?php if (empty($item['content_text']) && empty($item['content_url'])): ?><span class="muted">Belum ada isi materi.</span><?php endif; ?></td><td><?= h($item['course_title']) ?></td><td><?= h($item['module_title'] ?: '—') ?></td><td><?= h(content_type_name($item['content_type'])) ?></td><td><?= badge($item['is_published'] ? 'Terbit' : 'Draf', $item['is_published'] ? 'success' : 'neutral') ?></td><td><div class="row-actions"><?= admin_record_action_form('toggle_record', 'material', (int)$item['id'], $item['is_published'] ? 'Sembunyikan' : 'Terbitkan', 'materials') ?><?= admin_record_action_form('delete_record', 'material', (int)$item['id'], 'Hapus', 'materials', true, 'Hapus materi ini? Penghapusan ditolak jika sudah ada progres peserta.') ?></div></td></tr><?php endforeach; ?></tbody></table></div><?php endif; ?></section>
                <?php
            } else {
                $materials = rows($pdo, "SELECT m.*,c.title AS course_title,cm.title AS module_title,(p.id IS NOT NULL) AS completed FROM materials m JOIN courses c ON c.id=m.course_id JOIN enrollments e ON e.course_id=c.id AND e.user_id=? AND e.status='active' LEFT JOIN course_modules cm ON cm.id=m.module_id LEFT JOIN user_progress p ON p.material_id=m.id AND p.user_id=? WHERE m.is_published=1 AND c.status='published' AND (m.module_id IS NULL OR cm.is_active=1) ORDER BY c.title,cm.sort_order,m.sort_order", array($userId, $userId));
                ?>
                <section class="panel"><div class="panel-head"><div><h3>Materi pembelajaran</h3><p class="muted">Progres akan tersimpan saat Anda menandai materi selesai.</p></div></div><?php if (!$materials): ?><div class="empty-state"><strong>Belum ada materi untuk Anda</strong><span>Daftar ke kursus gratis melalui katalog untuk membuka materi.</span><a class="button button-soft" href="index.php?page=courses">Buka katalog kursus</a></div><?php else: ?><div class="material-list"><?php foreach ($materials as $item): ?><article class="material-row"><div class="material-number"><?= $item['completed'] ? '✓' : '▤' ?></div><div class="material-copy"><strong><?= h($item['title']) ?></strong><span><?= h($item['course_title'] . ($item['module_title'] ? ' · ' . $item['module_title'] : '')) ?></span><?php if ($item['content_text']): ?><p><?= nl2br(h($item['content_text'])) ?></p><?php endif; ?><?php if ($item['content_url']): ?><?php $videoId = null; $videoHost = strtolower((string)parse_url($item['content_url'], PHP_URL_HOST)); if (in_array($videoHost, array('youtube.com','www.youtube.com','m.youtube.com','youtu.be'), true)) { if ($videoHost === 'youtu.be') { $videoId = trim((string)parse_url($item['content_url'], PHP_URL_PATH), '/'); } else { parse_str((string)parse_url($item['content_url'], PHP_URL_QUERY), $videoQuery); $videoId = $videoQuery['v'] ?? null; if (!$videoId && preg_match('~/(?:embed|shorts)/([A-Za-z0-9_-]{6,})~', (string)parse_url($item['content_url'], PHP_URL_PATH), $videoMatch)) $videoId = $videoMatch[1]; } } ?><?php if ($videoId && preg_match('/^[A-Za-z0-9_-]{6,}$/', $videoId)): ?><div class="lesson-video"><iframe src="https://www.youtube-nocookie.com/embed/<?= h($videoId) ?>" title="Video pembelajaran: <?= h($item['title']) ?>" loading="lazy" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe></div><?php else: ?><a class="text-link" href="<?= h($item['content_url']) ?>" target="_blank" rel="noopener noreferrer">Buka <?= h($item['content_type'] === 'file' ? 'berkas' : 'tautan') ?> ↗</a><?php endif; ?><?php endif; ?></div><?php if (!$item['completed']): ?><form method="post"><?= csrf_input() ?><input type="hidden" name="action" value="complete_material"><input type="hidden" name="material_id" value="<?= h($item['id']) ?>"><button class="button button-soft">Tandai selesai</button></form><?php else: ?><?= badge('Selesai', 'success') ?><?php endif; ?></article><?php endforeach; ?></div><?php endif; ?></section>
                <?php
            }
            break;

        case 'assessments':
            if ($admin) {
                $courses = course_options($pdo);
                $items = rows($pdo, 'SELECT a.*,c.title AS course_title,(SELECT COUNT(*) FROM assessment_submissions s WHERE s.assessment_id=a.id) AS submissions FROM assessments a JOIN courses c ON c.id=a.course_id ORDER BY a.created_at DESC');
                ?>
                <section class="panel"><div class="panel-head"><div><h3>Buat evaluasi</h3><p class="muted">Kuis, tugas, atau evaluasi dapat ditandai sebagai opsional.</p></div></div><form method="post" class="form-grid"><?= csrf_input() ?><input type="hidden" name="action" value="create_assessment"><label>Kursus<select name="course_id" required><option value="">Pilih kursus</option><?php foreach ($courses as $item): ?><option value="<?= h($item['id']) ?>"><?= h($item['title']) ?></option><?php endforeach; ?></select></label><label>Judul<input name="title" required></label><label>Jenis<select name="assessment_type"><option value="quiz">Kuis</option><option value="assignment">Tugas</option><option value="evaluation">Evaluasi</option></select></label><label>Batas waktu<input name="due_at" type="datetime-local"></label><label class="span-two">Instruksi<textarea name="instructions" rows="3"></textarea></label><label class="check-row"><input type="checkbox" name="is_optional" value="1"> Fitur opsional</label><label class="check-row"><input type="checkbox" name="is_published" value="1"> Terbitkan untuk peserta</label><button class="button button-primary">＋ Simpan evaluasi</button></form></section>
                <?php
            } else {
                $items = rows($pdo, "SELECT a.*,c.title AS course_title FROM assessments a JOIN courses c ON c.id=a.course_id JOIN enrollments e ON e.course_id=c.id AND e.user_id=? AND e.status='active' WHERE a.is_published=1 AND c.status='published' ORDER BY a.due_at IS NULL,a.due_at", array($userId));
                ?>
                <section class="panel"><div class="panel-head"><div><h3>Evaluasi kursus</h3><p class="muted">Kirim jawaban sesuai instruksi pada setiap evaluasi.</p></div></div><?php if (!$items): ?><div class="empty-state"><strong>Belum ada evaluasi</strong><span>Kuis dan tugas yang tersedia akan muncul di sini.</span></div><?php else: ?><div class="assessment-grid"><?php foreach ($items as $item): ?><article class="assessment-card"><div class="panel-head"><div><span class="eyebrow"><?= h($item['course_title']) ?></span><h3><?= h($item['title']) ?></h3></div><?= badge($item['assessment_type'] === 'assignment' ? 'Tugas' : ($item['assessment_type'] === 'quiz' ? 'Kuis' : 'Evaluasi'), 'info') ?></div><p><?= nl2br(h($item['instructions'] ?: 'Ikuti instruksi dari instruktur.')) ?></p><?php if ($item['due_at']): ?><p class="muted">Batas waktu: <?= h(date('d M Y H:i', strtotime($item['due_at']))) ?></p><?php endif; ?><form method="post" class="form-stack"><?= csrf_input() ?><input type="hidden" name="action" value="submit_assessment"><input type="hidden" name="assessment_id" value="<?= h($item['id']) ?>"><label>Jawaban<textarea name="response_text" rows="4" required></textarea></label><button class="button button-primary">Kirim jawaban</button></form></article><?php endforeach; ?></div><?php endif; ?></section>
                <?php
            }
            if ($admin) {
                $items = rows($pdo, 'SELECT a.*,c.title AS course_title,(SELECT COUNT(*) FROM assessment_submissions s WHERE s.assessment_id=a.id) AS submissions FROM assessments a JOIN courses c ON c.id=a.course_id ORDER BY a.created_at DESC');
            }
            if ($admin): ?>
              <section class="panel"><div class="panel-head"><div><h3>Daftar evaluasi</h3><p class="muted">Tarik publikasi evaluasi atau hapus jika belum ada jawaban.</p></div></div><?php if (!$items): ?><div class="empty-state">Belum ada evaluasi.</div><?php else: ?><div class="table-wrap"><table><thead><tr><th>Evaluasi</th><th>Kursus</th><th>Jenis</th><th>Jawaban</th><th>Status</th><th>Aksi</th></tr></thead><tbody><?php foreach ($items as $item): ?><tr><td><strong><?= h($item['title']) ?></strong></td><td><?php if (!empty($item['content_text'])): ?><div><?= nl2br(h($item['content_text'])) ?></div><?php endif; ?><?php if (!empty($item['content_url'])): ?><?php $adminExt = strtolower(pathinfo((string)parse_url($item['content_url'], PHP_URL_PATH), PATHINFO_EXTENSION)); ?><?php if (in_array($adminExt, array('mp4','mov'), true)): ?><video controls preload="metadata" style="display:block;width:min(420px,100%);max-height:240px;margin-top:8px"><source src="<?= h($item['content_url']) ?>"></video><?php else: ?><a href="<?= h($item['content_url']) ?>" target="_blank" rel="noopener noreferrer">Buka materi ↗</a><?php endif; ?><?php endif; ?><?php if (empty($item['content_text']) && empty($item['content_url'])): ?><span class="muted">Belum ada isi materi.</span><?php endif; ?></td><td><?= h($item['course_title']) ?></td><td><?= h(assessment_type_name($item['assessment_type'])) ?></td><td><?= h($item['submissions']) ?></td><td><?= badge($item['is_published'] ? 'Terbit' : 'Draf', $item['is_published'] ? 'success' : 'neutral') ?></td><td><div class="row-actions"><?= admin_record_action_form('toggle_record', 'assessment', (int)$item['id'], $item['is_published'] ? 'Sembunyikan' : 'Terbitkan', 'assessments') ?><?= admin_record_action_form('delete_record', 'assessment', (int)$item['id'], 'Hapus', 'assessments', true, 'Hapus evaluasi ini? Penghapusan ditolak jika sudah ada jawaban peserta.') ?></div></td></tr><?php endforeach; ?></tbody></table></div><?php endif; ?></section>
            <?php endif;
            break;

        case 'certificates':
            if ($admin) {
                $students = user_options($pdo);
                $courses = course_options($pdo);
                $certificates = rows($pdo, 'SELECT x.id,x.certificate_no,x.issued_at,x.is_revoked,u.full_name,c.title AS course_title FROM certificates x JOIN users u ON u.id=x.user_id JOIN courses c ON c.id=x.course_id ORDER BY x.issued_at DESC');
                ?>
                <div class="content-grid two-col"><section class="panel"><div class="panel-head"><div><h3>Terbitkan sertifikat</h3><p class="muted">Sertifikat akan tercatat dengan nomor unik.</p></div></div><form method="post" class="form-stack"><?= csrf_input() ?><input type="hidden" name="action" value="issue_certificate"><label>Peserta<select name="user_id" required><option value="">Pilih peserta</option><?php foreach ($students as $item): ?><option value="<?= h($item['id']) ?>"><?= h($item['full_name'] . ' (@' . $item['username'] . ')') ?></option><?php endforeach; ?></select></label><label>Kursus<select name="course_id" required><option value="">Pilih kursus</option><?php foreach ($courses as $item): ?><option value="<?= h($item['id']) ?>"><?= h($item['title']) ?></option><?php endforeach; ?></select></label><button class="button button-primary">＋ Terbitkan sertifikat</button></form></section><section class="panel"><div class="panel-head"><div><h3>Ketentuan sertifikat</h3><p class="muted">Nomor sertifikat dibuat otomatis.</p></div></div><div class="notice notice-info">Pastikan penyelesaian kursus sudah dikonfirmasi sebelum menerbitkan sertifikat.</div></section></div>
                <section class="panel"><div class="panel-head"><div><h3>Riwayat sertifikat</h3><p class="muted">Cabut sertifikat yang tidak berlaku; hapus setelah dicabut.</p></div></div><?php if (!$certificates): ?><div class="empty-state">Belum ada sertifikat.</div><?php else: ?><div class="table-wrap"><table><thead><tr><th>Nomor</th><th>Peserta</th><th>Kursus</th><th>Tanggal terbit</th><th>Status</th><th>Aksi</th></tr></thead><tbody><?php foreach ($certificates as $item): ?><tr><td><?= h($item['certificate_no']) ?></td><td><?= h($item['full_name']) ?></td><td><?= h($item['course_title']) ?></td><td><?= h(date('d M Y', strtotime($item['issued_at']))) ?></td><td><?= badge($item['is_revoked'] ? 'Dicabut' : 'Berlaku', $item['is_revoked'] ? 'warning' : 'success') ?></td><td><div class="row-actions"><?= admin_record_action_form('toggle_record', 'certificate', (int)$item['id'], $item['is_revoked'] ? 'Pulihkan' : 'Cabut', 'certificates') ?><?php if ($item['is_revoked']): ?><?= admin_record_action_form('delete_record', 'certificate', (int)$item['id'], 'Hapus', 'certificates', true, 'Hapus sertifikat yang sudah dicabut ini?') ?><?php endif; ?></div></td></tr><?php endforeach; ?></tbody></table></div><?php endif; ?></section>
                <?php
            } else {
                $certificates = rows($pdo, 'SELECT x.certificate_no,x.issued_at,c.title AS course_title FROM certificates x JOIN courses c ON c.id=x.course_id WHERE x.user_id=? AND x.is_revoked=0 ORDER BY x.issued_at DESC', array($userId));
                ?>
                <section class="panel"><div class="panel-head"><div><h3>Sertifikat saya</h3><p class="muted">Daftar sertifikat yang telah diterbitkan.</p></div></div><?php if (!$certificates): ?><div class="empty-state"><strong>Belum ada sertifikat</strong><span>Sertifikat akan tampil setelah diterbitkan oleh administrator.</span></div><?php else: ?><div class="table-wrap"><table><thead><tr><th>Program</th><th>Nomor sertifikat</th><th>Tanggal terbit</th></tr></thead><tbody><?php foreach ($certificates as $item): ?><tr><td><?= h($item['course_title']) ?></td><td><?= h($item['certificate_no']) ?></td><td><?= h(date('d M Y', strtotime($item['issued_at']))) ?></td></tr><?php endforeach; ?></tbody></table></div><?php endif; ?></section>
                <?php
            }
            break;

        case 'vouchers':
            if ($admin) {
                $courses = course_options($pdo);
                $vouchers = rows($pdo, 'SELECT v.*,c.title AS course_title,(SELECT COUNT(*) FROM voucher_redemptions r WHERE r.voucher_id=v.id) AS used_count FROM vouchers v LEFT JOIN courses c ON c.id=v.course_id ORDER BY v.created_at DESC');
                ?>
                <div class="content-grid two-col"><section class="panel"><div class="panel-head"><div><h3>Buat voucher</h3><p class="muted">Atur manfaat, kuota, dan masa berlaku.</p></div></div><form method="post" class="form-grid"><?= csrf_input() ?><input type="hidden" name="action" value="create_voucher"><label>Kode voucher<input name="code" required minlength="4" maxlength="80" placeholder="FKKSM2026"></label><label>Nama voucher<input name="title" required></label><label>Jenis manfaat<select name="benefit_type"><option value="free_access">Akses gratis</option><option value="discount">Diskon</option><option value="program_access">Akses program</option></select></label><label>Nilai diskon (Rp)<input name="discount_value" type="number" min="0" value="0"></label><label>Kursus tujuan<select name="course_id"><option value="">Semua / tidak ditentukan</option><?php foreach ($courses as $item): ?><option value="<?= h($item['id']) ?>"><?= h($item['title']) ?></option><?php endforeach; ?></select></label><label>Kuota penggunaan<input name="max_uses" type="number" min="1" placeholder="Tanpa batas"></label><label class="span-two">Berlaku sampai<input name="expires_at" type="datetime-local"></label><label class="span-two">Keterangan manfaat<input name="benefit_description"></label><label class="check-row"><input type="checkbox" name="is_active" value="1" checked> Aktifkan voucher</label><button class="button button-primary">＋ Simpan voucher</button></form></section><section class="panel"><div class="panel-head"><div><h3>Status voucher</h3><p class="muted">Kondisi voucher yang tersedia.</p></div></div><div class="status-stack"><div class="status-item success"><b>Tersedia</b><span>Kode dapat ditukarkan sesuai ketentuannya.</span></div><div class="status-item neutral"><b>Digunakan</b><span>Kode sudah ditukarkan oleh peserta.</span></div><div class="status-item warning"><b>Kedaluwarsa</b><span>Masa berlaku kode sudah berakhir.</span></div><div class="status-item danger"><b>Tidak valid</b><span>Kode tidak dapat digunakan atau kuota habis.</span></div></div></section></div>
                <section class="panel"><div class="panel-head"><div><h3>Data voucher</h3><p class="muted">Jeda voucher atau hapus voucher yang belum pernah ditukarkan.</p></div></div><?php if (!$vouchers): ?><div class="empty-state">Belum ada voucher.</div><?php else: ?><div class="table-wrap"><table><thead><tr><th>Kode / manfaat</th><th>Kursus</th><th>Penggunaan</th><th>Berlaku sampai</th><th>Status</th><th>Aksi</th></tr></thead><tbody><?php foreach ($vouchers as $item): ?><tr><td><strong><?= h($item['code']) ?></strong><small class="table-subtitle"><?= h($item['title']) ?></small></td><td><?= h($item['course_title'] ?: 'Tidak ditentukan') ?></td><td><?= h($item['used_count']) ?><?= $item['max_uses'] ? ' / ' . h($item['max_uses']) : '' ?></td><td><?= h($item['expires_at'] ? date('d M Y', strtotime($item['expires_at'])) : 'Tanpa batas') ?></td><td><?= badge($item['status'] === 'active' ? 'Aktif' : status_name($item['status']), $item['status'] === 'active' ? 'success' : 'neutral') ?></td><td><div class="row-actions"><?= admin_record_action_form('toggle_record', 'voucher', (int)$item['id'], $item['status'] === 'active' ? 'Jeda' : 'Aktifkan', 'vouchers') ?><?= admin_record_action_form('delete_record', 'voucher', (int)$item['id'], 'Hapus', 'vouchers', true, 'Hapus voucher ini? Penghapusan ditolak jika sudah pernah ditukarkan.') ?></div></td></tr><?php endforeach; ?></tbody></table></div><?php endif; ?></section>
                <?php
            } else {
                $history = rows($pdo, 'SELECT v.code,v.title,r.redeemed_at FROM voucher_redemptions r JOIN vouchers v ON v.id=r.voucher_id WHERE r.user_id=? ORDER BY r.redeemed_at DESC', array($userId));
                ?>
                <div class="content-grid two-col"><section class="panel"><div class="panel-head"><div><h3>Tukarkan kode voucher</h3><p class="muted">Gunakan kode akses yang diberikan administrator.</p></div></div><form method="post" class="form-stack"><?= csrf_input() ?><input type="hidden" name="action" value="redeem_voucher"><label>Kode voucher<input name="code" required placeholder="Masukkan kode voucher"></label><button class="button button-primary">✓ Periksa dan tukarkan</button></form></section><section class="panel"><div class="panel-head"><div><h3>Manfaat voucher</h3><p class="muted">Ketentuan mengikuti kode yang Anda masukkan.</p></div></div><div class="empty-state"><strong>Akses, diskon, atau program</strong><span>Rincian manfaat akan ditampilkan setelah kode berhasil ditukarkan.</span></div></section></div>
                <section class="panel"><div class="panel-head"><div><h3>Riwayat penukaran</h3><p class="muted">Voucher yang pernah digunakan.</p></div></div><?php if (!$history): ?><div class="empty-state">Belum ada voucher yang ditukarkan.</div><?php else: ?><div class="table-wrap"><table><thead><tr><th>Kode</th><th>Manfaat</th><th>Tanggal</th></tr></thead><tbody><?php foreach ($history as $item): ?><tr><td><?= h($item['code']) ?></td><td><?= h($item['title']) ?></td><td><?= h(date('d M Y H:i', strtotime($item['redeemed_at']))) ?></td></tr><?php endforeach; ?></tbody></table></div><?php endif; ?></section>
                <?php
            }
            break;

        case 'subscriptions':
            if ($admin) {
                $plans = rows($pdo, 'SELECT * FROM plans ORDER BY created_at DESC');
                $subscriptions = rows($pdo, 'SELECT s.*,u.full_name,p.name AS plan_name FROM subscriptions s JOIN users u ON u.id=s.user_id JOIN plans p ON p.id=s.plan_id ORDER BY s.created_at DESC LIMIT 100');
                $users = user_options($pdo);
                ?>
                <div class="content-grid two-col"><section class="panel"><div class="panel-head"><div><h3>Paket layanan &amp; periode</h3><p class="muted">Buat pilihan akses gratis atau berbayar.</p></div></div><form method="post" class="form-grid"><?= csrf_input() ?><input type="hidden" name="action" value="create_plan"><label>Nama paket<input name="name" required></label><label>Harga (Rp)<input name="price" type="number" min="0" step="1000" value="0"></label><label>Masa aktif (hari)<input name="duration_days" type="number" min="1" placeholder="Tidak ditentukan"></label><label class="span-two">Deskripsi<textarea name="description" rows="2"></textarea></label><label class="check-row"><input type="checkbox" name="is_active" value="1" checked> Aktifkan paket</label><button class="button button-primary">＋ Tambah paket</button></form><?php if ($plans): ?><div class="compact-list compact-list-spaced"><?php foreach ($plans as $plan): ?><div class="compact-row"><span><strong><?= h($plan['name']) ?></strong><small><?= h(money($plan['price']) . ' · ' . ($plan['duration_days'] ? $plan['duration_days'] . ' hari' : 'Durasi fleksibel')) ?></small></span><div class="row-actions"><?= badge($plan['status'] === 'active' ? 'Aktif' : 'Nonaktif', $plan['status'] === 'active' ? 'success' : 'neutral') ?><?= admin_record_action_form('toggle_record', 'plan', (int)$plan['id'], $plan['status'] === 'active' ? 'Nonaktifkan' : 'Aktifkan', 'subscriptions') ?><?= admin_record_action_form('delete_record', 'plan', (int)$plan['id'], 'Hapus', 'subscriptions', true, 'Hapus paket ini? Penghapusan ditolak jika ada riwayat langganan.') ?></div></div><?php endforeach; ?></div><?php endif; ?></section>
                <section class="panel"><div class="panel-head"><div><h3>Pengaturan akses pengguna</h3><p class="muted">Tetapkan paket dan masa aktif untuk peserta.</p></div></div><form method="post" class="form-stack"><?= csrf_input() ?><input type="hidden" name="action" value="assign_subscription"><label>Pengguna<select name="user_id" required><option value="">Pilih peserta</option><?php foreach ($users as $item): ?><option value="<?= h($item['id']) ?>"><?= h($item['full_name']) ?></option><?php endforeach; ?></select></label><label>Paket layanan<select name="plan_id" required><option value="">Pilih paket</option><?php foreach ($plans as $plan): ?><?php if ($plan['status'] === 'active'): ?><option value="<?= h($plan['id']) ?>"><?= h($plan['name'] . ' — ' . money($plan['price'])) ?></option><?php endif; ?><?php endforeach; ?></select></label><label>Masa aktif (hari)<input name="duration_days" type="number" min="1" required value="30"></label><button class="button button-primary">✓ Berikan akses</button></form><div class="notice notice-warning">Pembayaran belum terhubung. Status paket dicatat oleh administrator.</div></section></div>
                <section class="panel"><div class="panel-head"><div><h3>Data langganan</h3><p class="muted">Batalkan akses atau hapus catatan yang sudah tidak aktif.</p></div></div><?php if (!$subscriptions): ?><div class="empty-state">Belum ada langganan.</div><?php else: ?><div class="table-wrap"><table><thead><tr><th>Pengguna</th><th>Paket</th><th>Mulai</th><th>Berakhir</th><th>Status</th><th>Aksi</th></tr></thead><tbody><?php foreach ($subscriptions as $item): ?><tr><td><?= h($item['full_name']) ?></td><td><?= h($item['plan_name']) ?></td><td><?= h($item['starts_at'] ? date('d M Y', strtotime($item['starts_at'])) : '—') ?></td><td><?= h($item['ends_at'] ? date('d M Y', strtotime($item['ends_at'])) : '—') ?></td><td><?= badge(status_name($item['status']), $item['status'] === 'active' ? 'success' : 'neutral') ?></td><td><div class="row-actions"><?php if ($item['status'] !== 'expired'): ?><?= admin_record_action_form('toggle_record', 'subscription', (int)$item['id'], $item['status'] === 'cancelled' ? 'Aktifkan' : 'Batalkan', 'subscriptions') ?><?php endif; ?><?php if (in_array($item['status'], array('cancelled', 'expired'), true)): ?><?= admin_record_action_form('delete_record', 'subscription', (int)$item['id'], 'Hapus', 'subscriptions', true, 'Hapus riwayat langganan ini?') ?><?php endif; ?></div></td></tr><?php endforeach; ?></tbody></table></div><?php endif; ?></section>
                <?php
            } else {
                $plans = rows($pdo, "SELECT * FROM plans WHERE status='active' ORDER BY price,name");
                $subscriptions = rows($pdo, 'SELECT s.*,p.name AS plan_name,p.description,p.price FROM subscriptions s JOIN plans p ON p.id=s.plan_id WHERE s.user_id=? ORDER BY s.created_at DESC', array($userId));
                ?>
                <section class="welcome-banner"><div><h2>Langganan &amp; akses belajar</h2><p>Pilih akses sesuai periode atau paket layanan yang tersedia.</p></div><span class="banner-mark">▣</span></section><div class="course-grid"><?php if (!$plans): ?><section class="panel empty-state"><strong>Belum ada paket tersedia</strong><span>Informasi paket akan muncul setelah ditetapkan administrator.</span></section><?php else: ?><?php foreach ($plans as $plan): ?><article class="course-card"><div class="course-card-top"><span class="course-icon">▣</span><?= badge($plan['price'] > 0 ? 'Berbayar' : 'Gratis', $plan['price'] > 0 ? 'warning' : 'success') ?></div><h3><?= h($plan['name']) ?></h3><p><?= h($plan['description'] ?: 'Akses pembelajaran FKKSM MM2100.') ?></p><strong class="price-large"><?= h(money($plan['price'])) ?></strong><span class="muted"><?= h($plan['duration_days'] ? $plan['duration_days'] . ' hari' : 'Periode sesuai ketentuan') ?></span><p class="muted">Hubungi administrator untuk mengaktifkan paket.</p></article><?php endforeach; ?><?php endif; ?></div>
                <section class="panel"><div class="panel-head"><div><h3>Langganan pengguna</h3><p class="muted">Status paket akses Anda.</p></div></div><?php if (!$subscriptions): ?><div class="empty-state">Belum ada paket aktif.</div><?php else: ?><div class="table-wrap"><table><thead><tr><th>Paket</th><th>Harga</th><th>Masa aktif</th><th>Status</th></tr></thead><tbody><?php foreach ($subscriptions as $item): ?><tr><td><?= h($item['plan_name']) ?></td><td><?= h(money($item['price'])) ?></td><td><?= h($item['ends_at'] ? date('d M Y', strtotime($item['ends_at'])) : '—') ?></td><td><?= badge(status_name($item['status']), $item['status'] === 'active' ? 'success' : 'neutral') ?></td></tr><?php endforeach; ?></tbody></table></div><?php endif; ?></section>
                <?php
            }
            break;

        case 'leads':
            require_admin_action($user);
            $leads = rows($pdo, 'SELECT l.*,u.full_name AS creator FROM leads l LEFT JOIN users u ON u.id=l.created_by ORDER BY l.created_at DESC LIMIT 100');
            ?>
            <section class="welcome-banner"><div><h2>Pengelolaan Calon Peserta</h2><p>Pantau registrasi, minat program, dan tindak lanjut calon peserta.</p></div><span class="banner-mark">♙</span></section>
            <div class="content-grid two-col"><section class="panel"><div class="panel-head"><div><h3>Data calon peserta / pelanggan</h3><p class="muted">Simpan kontak dan minat program.</p></div></div><form method="post" class="form-stack"><?= csrf_input() ?><input type="hidden" name="action" value="create_lead"><label>Nama calon peserta<input name="full_name" required></label><label>Alamat email<input name="email" type="email"></label><label>Nomor kontak<input name="phone"></label><label>Sumber informasi<input name="source" placeholder="Contoh: acara, situs, rekomendasi"></label><label>Minat program<input name="interest"></label><label>Catatan<textarea name="notes" rows="3"></textarea></label><button class="button button-primary">＋ Simpan data calon peserta</button></form></section><section class="panel"><div class="panel-head"><div><h3>Aktivitas calon peserta</h3><p class="muted">Ringkasan status yang tercatat.</p></div></div><div class="stats-grid compact-stats"><?php foreach (array(array('Baru','new'),array('Dihubungi','contacted'),array('Terkualifikasi','qualified'),array('Dikonversi','converted')) as $item): ?><div class="mini-stat"><span><?= h($item[0]) ?></span><strong><?= h(one_value($pdo, 'SELECT COUNT(*) FROM leads WHERE status=?', array($item[1]))) ?></strong></div><?php endforeach; ?></div><div class="notice notice-info">Tutup calon peserta yang sudah tidak aktif dan buka kembali bila diperlukan.</div></section></div>
            <section class="panel"><div class="panel-head"><div><h3>Data calon peserta</h3><p class="muted">Informasi kontak dan minat program.</p></div><input class="table-search" data-table-search="leadTable" placeholder="Cari calon peserta..."></div><?php if (!$leads): ?><div class="empty-state">Belum ada data calon peserta.</div><?php else: ?><div class="table-wrap"><table id="leadTable"><thead><tr><th>Nama</th><th>Kontak</th><th>Sumber</th><th>Minat program</th><th>Status</th><th>Dicatat</th><th>Aksi</th></tr></thead><tbody><?php foreach ($leads as $item): ?><tr><td><?= h($item['full_name']) ?></td><td><?= h($item['email'] ?: $item['phone'] ?: '—') ?></td><td><?= h($item['source'] ?: '—') ?></td><td><?= h($item['interest'] ?: '—') ?></td><td><?= badge(status_name($item['status']), $item['status'] === 'new' ? 'info' : 'neutral') ?></td><td><?= h(date('d M Y', strtotime($item['created_at']))) ?></td><td><div class="row-actions"><?= admin_record_action_form('toggle_record', 'lead', (int)$item['id'], $item['status'] === 'closed' ? 'Buka kembali' : 'Tutup', 'leads') ?><?= admin_record_action_form('delete_record', 'lead', (int)$item['id'], 'Hapus', 'leads', true, 'Hapus data calon peserta ini?') ?></div></td></tr><?php endforeach; ?></tbody></table></div><?php endif; ?></section>
            <?php
            break;

        case 'reports':
            require_admin_action($user);
            $logs = rows($pdo, 'SELECT l.*,u.full_name FROM activity_logs l LEFT JOIN users u ON u.id=l.user_id ORDER BY l.created_at DESC LIMIT 30');
            $enrollments = rows($pdo, 'SELECT e.id,e.status,e.enrolled_at,u.full_name,c.title AS course_title FROM enrollments e JOIN users u ON u.id=e.user_id JOIN courses c ON c.id=e.course_id ORDER BY e.enrolled_at DESC LIMIT 100');
            ?>
            <section class="welcome-banner"><div><h2>Pengelolaan Calon Peserta &amp; Laporan</h2><p>Pemantauan registrasi, penukaran voucher, aktivitas peserta, dan layanan.</p></div><span class="banner-mark">▥</span></section>
            <div class="stats-grid"><?php foreach (array(array('Registrasi','SELECT COUNT(*) FROM users'),array('Pendaftaran kursus','SELECT COUNT(*) FROM enrollments'),array('Penukaran voucher','SELECT COUNT(*) FROM voucher_redemptions'),array('Aktivitas sistem','SELECT COUNT(*) FROM activity_logs')) as $item): ?><article class="stat-card"><span class="stat-label"><?= h($item[0]) ?></span><strong><?= h(one_value($pdo, $item[1])) ?></strong></article><?php endforeach; ?></div>
            <div class="content-grid two-col"><section class="panel"><div class="panel-head"><div><h3>Pemantauan registrasi &amp; aktivitas</h3><p class="muted">Log aktivitas terbaru.</p></div></div><?php if (!$logs): ?><div class="empty-state">Belum ada aktivitas.</div><?php else: ?><div class="table-wrap"><table><thead><tr><th>Waktu</th><th>Pengguna</th><th>Aktivitas</th></tr></thead><tbody><?php foreach (array_slice($logs, 0, 10) as $item): ?><tr><td><?= h(date('d M Y H:i', strtotime($item['created_at']))) ?></td><td><?= h($item['full_name'] ?: 'Sistem') ?></td><td><?= h(activity_name($item['action'])) ?></td></tr><?php endforeach; ?></tbody></table></div><?php endif; ?></section><section class="panel"><div class="panel-head"><div><h3>Aktivitas peserta &amp; layanan</h3><p class="muted">Ringkasan data yang tercatat.</p></div></div><div class="role-list"><div><b>Kursus &amp; pembelajaran</b><span><?= h(one_value($pdo, 'SELECT COUNT(*) FROM courses')) ?> kursus · <?= h(one_value($pdo, 'SELECT COUNT(*) FROM materials')) ?> materi</span></div><div><b>Langganan</b><span><?= h(one_value($pdo, 'SELECT COUNT(*) FROM subscriptions')) ?> paket pengguna</span></div><div><b>Calon peserta</b><span><?= h(one_value($pdo, 'SELECT COUNT(*) FROM leads')) ?> kontak tercatat</span></div></div></section></div>
            <section class="panel"><div class="panel-head"><div><h3>Pendaftaran kursus</h3><p class="muted">Hentikan akses peserta atau buka kembali pendaftaran yang dibatalkan.</p></div></div><?php if (!$enrollments): ?><div class="empty-state">Belum ada pendaftaran kursus.</div><?php else: ?><div class="table-wrap"><table><thead><tr><th>Peserta</th><th>Kursus</th><th>Tanggal daftar</th><th>Status</th><th>Aksi</th></tr></thead><tbody><?php foreach ($enrollments as $enrollment): ?><tr><td><?= h($enrollment['full_name']) ?></td><td><?= h($enrollment['course_title']) ?></td><td><?= h(date('d M Y', strtotime($enrollment['enrolled_at']))) ?></td><td><?= badge(status_name($enrollment['status']), $enrollment['status'] === 'active' ? 'success' : 'neutral') ?></td><td><div class="row-actions"><?php if ($enrollment['status'] !== 'completed'): ?><?= admin_record_action_form('toggle_record', 'enrollment', (int)$enrollment['id'], $enrollment['status'] === 'cancelled' ? 'Aktifkan' : 'Nonaktifkan', 'reports') ?><?php else: ?><span class="muted">Kursus selesai</span><?php endif; ?></div></td></tr><?php endforeach; ?></tbody></table></div><?php endif; ?></section>
            <?php
            break;

        case 'settings':
            require_admin_action($user);
            $settingRows = rows($pdo, 'SELECT setting_key,setting_value FROM settings');
            $settings = array();
            foreach ($settingRows as $item) { $settings[$item['setting_key']] = $item['setting_value']; }
            $events = explode(',', (string)($settings['notification_events'] ?? ''));
            $logs = rows($pdo, 'SELECT l.*,u.full_name FROM activity_logs l LEFT JOIN users u ON u.id=l.user_id ORDER BY l.created_at DESC LIMIT 20');
            ?>
            <div class="content-grid two-col"><section class="panel"><div class="panel-head"><div><h3>Notifikasi</h3><p class="muted">Pilih kanal dan peristiwa yang perlu dicatat.</p></div><?= badge('Pengaturan lokal', 'warning') ?></div><form method="post" class="form-stack"><?= csrf_input() ?><input type="hidden" name="action" value="save_settings"><label>Kanal notifikasi<select name="notification_channel"><option value="in_app" <?= ($settings['notification_channel'] ?? '') === 'in_app' ? 'selected' : '' ?>>Dalam aplikasi</option><option value="email" <?= ($settings['notification_channel'] ?? '') === 'email' ? 'selected' : '' ?>>Email (memerlukan konfigurasi SMTP)</option><option value="other" <?= ($settings['notification_channel'] ?? '') === 'other' ? 'selected' : '' ?>>Kanal lain</option></select></label><fieldset><legend>Peristiwa notifikasi</legend><?php foreach (array('course_enrollment' => 'Pendaftaran kursus', 'assessment_submission' => 'Pengumpulan evaluasi', 'voucher_redemption' => 'Penukaran voucher', 'new_lead' => 'Data calon peserta baru') as $key => $label): ?><label class="check-row"><input type="checkbox" name="notification_events[]" value="<?= h($key) ?>" <?= in_array($key, $events, true) ? 'checked' : '' ?>> <?= h($label) ?></label><?php endforeach; ?></fieldset><label>Batas sesi (menit)<input type="number" name="security_session_minutes" min="15" max="240" value="<?= h($settings['security_session_minutes'] ?? '60') ?>"></label><button class="button button-primary">✓ Simpan pengaturan</button></form></section>
            <section class="panel"><div class="panel-head"><div><h3>Hak akses &amp; keamanan akun</h3><p class="muted">Pengaturan keamanan dasar untuk akun LMS.</p></div></div><div class="security-row"><span>Pengaturan hak akses berdasarkan peran</span><?= badge('Aktif', 'success') ?></div><div class="security-row"><span>Kata sandi minimal 10 karakter</span><?= badge('Aktif', 'success') ?></div><div class="security-row"><span>Hash kata sandi dengan password_hash()</span><?= badge('Aktif', 'success') ?></div><div class="notice notice-info">Pembayaran, email, dan integrasi tambahan dapat dihubungkan setelah kredensial layanan disepakati.</div></section></div>
            <section class="panel"><div class="panel-head"><div><h3>Pencatatan aktivitas sistem</h3><p class="muted">Riwayat perubahan dan akses administrator.</p></div><input class="table-search" data-table-search="logTable" placeholder="Cari aktivitas sistem..."></div><?php if (!$logs): ?><div class="empty-state">Belum ada aktivitas sistem.</div><?php else: ?><div class="table-wrap"><table id="logTable"><thead><tr><th>Waktu aktivitas</th><th>Pengguna</th><th>Aktivitas sistem</th><th>Keterangan</th></tr></thead><tbody><?php foreach ($logs as $item): ?><tr><td><?= h(date('d M Y H:i', strtotime($item['created_at']))) ?></td><td><?= h($item['full_name'] ?: 'Sistem') ?></td><td><?= h(activity_name($item['action'])) ?></td><td><?= h($item['details'] ?: '—') ?></td></tr><?php endforeach; ?></tbody></table></div><?php endif; ?></section>
            <?php
            break;

        case 'profile':
            ?>
            <?php if ($user['must_change_password']): ?><div class="notice notice-warning"><strong>Ganti kata sandi awal</strong><p>Untuk melindungi akun admin, buat kata sandi baru sebelum menggunakan dashboard.</p></div><?php endif; ?>
            <div class="content-grid two-col"><section class="panel"><div class="panel-head"><div><h3>Profil akun</h3><p class="muted">Informasi akun Anda.</p></div></div><div class="profile-card"><span class="avatar avatar-large"><?= h(strtoupper(substr($user['full_name'], 0, 1))) ?></span><div><strong><?= h($user['full_name']) ?></strong><span><?= h($user['email'] ?: $user['username']) ?></span><?= badge(role_name($user['role']), 'info') ?></div></div></section><section class="panel"><div class="panel-head"><div><h3>Ubah kata sandi</h3><p class="muted">Gunakan sedikitnya 10 karakter.</p></div></div><form method="post" class="form-stack"><?= csrf_input() ?><input type="hidden" name="action" value="change_password"><?php if (empty($user['google_sub'])): ?><label>Kata sandi saat ini<input type="password" name="current_password" required autocomplete="current-password"></label><?php else: ?><p class="muted">Anda masuk melalui Google. Kata sandi saat ini tidak diperlukan untuk membuat kata sandi LMS.</p><?php endif; ?><label>Kata sandi baru<input type="password" name="new_password" required minlength="10" autocomplete="new-password"></label><label>Konfirmasi kata sandi baru<input type="password" name="new_password_confirmation" required minlength="10" autocomplete="new-password"></label><button class="button button-primary">✓ Simpan kata sandi</button></form></section></div>
            <?php
            break;
    }
}

if (!$user || in_array($page, array('login', 'register'), true)) {
    render_auth($page, $databaseError, $postError);
    exit;
}

$flashMessage = take_flash();
[$title, $subtitle] = page_title($page, $user);
$admin = is_admin($user);
$navItems = $admin
    ? array(array('dashboard','Dasbor','▦'),array('users','Manajemen Pengguna','♙'),array('courses','Kursus & Pembelajaran','▤'),array('materials','Materi Pembelajaran','▧'),array('vouchers','Voucher & Penukaran','▣'),array('subscriptions','Langganan','▥'),array('leads','Pengelolaan Calon Peserta','♧'),array('reports','Laporan','▤'),array('settings','Pengaturan Sistem','⚙'))
    : array(array('dashboard','Dasbor','▦'),array('courses','Modul','▤'),array('materials','Materi','▧'),array('assessments','Kuis','✓'),array('certificates','Sertifikat','♙'),array('vouchers','Voucher','▣'),array('subscriptions','Langganan','▥'));
$initials = strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $user['full_name']) ?: 'FK', 0, 2));
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
  <meta name="description" content="Sistem Manajemen Pembelajaran FKKSM MM2100">
  <title><?= h($title) ?> | FKKSM LMS</title>
  <link rel="stylesheet" href="assets/lms.css?v=10">
</head>
<body>
  <div class="app-shell">
    <aside class="sidebar" id="sidebar">
      <a class="brand-lockup" href="index.php?page=dashboard"><img class="brand-logo" src="../logo-transparent.png" alt="Logo FKKSM"><span class="brand-copy"><span class="brand-name">FKKSM MM2100</span><span class="brand-subtitle">SISTEM MANAJEMEN PEMBELAJARAN</span></span></a>
      <div class="sidebar-section-title"><?= $admin ? 'ADMINISTRATOR UTAMA' : 'MENU UTAMA' ?></div>
      <nav class="side-nav"><?php foreach ($navItems as $item) { echo nav_link($item[0], $item[1], $item[2], $page); } ?></nav>
      <div class="sidebar-promo" data-dismissible-promo><button class="promo-dismiss" type="button" aria-label="Tutup">×</button><span class="promo-icon">♧</span><strong>Kelola data dan akses</strong><p>Pengelolaan pembelajaran dan administrasi peserta.</p><a href="index.php?page=<?= $admin ? 'settings' : 'materials' ?>"><?= $admin ? 'Administrasi LMS' : 'Buka materi' ?> <b>→</b></a></div>
      <div class="sidebar-bottom"><?= nav_link('profile', 'Pengaturan akun', '⚙', $page) ?><form method="post"><?= csrf_input() ?><input type="hidden" name="action" value="logout"><button class="nav-link nav-logout" type="submit"><span class="nav-icon">↪</span><span>Keluar</span></button></form></div>
      <small class="sidebar-copyright">© <?= date('Y') ?> FKKSM</small>
    </aside>
    <main class="main-area">
      <header class="topbar"><button class="mobile-menu" type="button" aria-label="Buka menu" data-sidebar-toggle>☰</button><div class="topbar-welcome"><span>Selamat datang kembali</span><strong><?= h($admin ? 'Administrator Utama' : $user['full_name']) ?></strong></div><div class="topbar-tools"><label class="top-search">⌕ <input type="search" placeholder="Cari data..." aria-label="Cari data"></label><button class="icon-button" type="button" aria-label="Notifikasi">♧</button><a class="user-menu" href="index.php?page=profile"><span class="avatar"><?= h($initials) ?></span><span><strong><?= h($admin ? 'Administrator' : $user['full_name']) ?></strong><small><?= h(role_name($user['role'])) ?></small></span><b>⌄</b></a></div></header>
      <div class="page-wrap"><div class="page-kicker"><span><?= h($admin ? '6. Fitur Administrasi' : '2. Kursus & Pembelajaran') ?></span><span class="screen-label">FKKSM LMS</span></div><div class="page-heading"><div><h1><?= h($title) ?></h1><p><?= h($subtitle) ?></p></div></div>
        <?php if ($flashMessage): ?><div class="notice notice-<?= h($flashMessage['type']) ?>"><?= h($flashMessage['message']) ?></div><?php endif; ?>
        <?php if ($postError !== ''): ?><div class="notice notice-error"><?= h($postError) ?></div><?php endif; ?>
        <?php render_content($page, $user, $pdo); ?>
      </div>
      <footer class="app-footer"><span>FKKSM Sistem Manajemen Pembelajaran · <?= date('Y') ?></span><span><?= h($title) ?></span></footer>
    </main>
  </div>
  <script src="assets/lms.js?v=3" defer></script>
</body>
</html>

