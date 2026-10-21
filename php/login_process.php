<?php
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/audit.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../pages/login.html");
    exit;
}

$loginInput = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';
$remember = !empty($_POST['remember']);

if ($loginInput === '' || $password === '') {
    header("Location: ../pages/login.html?error=empty_fields");
    exit;
}

$conn = get_db_connection();
$stmt = mysqli_prepare($conn, "SELECT id, username, email, password_hash, role FROM users WHERE username = ? OR email = ? LIMIT 1");
if (!$stmt) {
    header("Location: ../pages/login.html?error=server_error");
    exit;
}

mysqli_stmt_bind_param($stmt, "ss", $loginInput, $loginInput);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (!$user || !password_verify($password, $user['password_hash'])) {
    header("Location: ../pages/login.html?error=invalid_credentials");
    exit;
}

login_user($user);

if ($remember) {
    $rawToken = bin2hex(random_bytes(32));
    $tokenHash = hash('sha256', $rawToken);
    $expiresAt = date('Y-m-d H:i:s', time() + (14 * 86400));
    $upd = mysqli_prepare($conn, "UPDATE users SET remember_token_hash = ?, remember_token_expires_at = ?, last_login = NOW() WHERE id = ?");
    if ($upd) {
        mysqli_stmt_bind_param($upd, "ssi", $tokenHash, $expiresAt, $user['id']);
        mysqli_stmt_execute($upd);
        mysqli_stmt_close($upd);
        setcookie('remember_token', $rawToken, [
            'expires' => time() + (14 * 86400),
            'path' => '/',
            'httponly' => true,
            'samesite' => 'Lax'
        ]);
    }
} else {
    $upd = mysqli_prepare($conn, "UPDATE users SET last_login = NOW() WHERE id = ?");
    if ($upd) {
        mysqli_stmt_bind_param($upd, "i", $user['id']);
        mysqli_stmt_execute($upd);
        mysqli_stmt_close($upd);
    }
}

record_audit_action($conn, (int)$user['id'], 'USER_LOGIN', 'users', (int)$user['id'], 'User logged in with role ' . $user['role']);

if ($user['role'] === 'admin') {
    header("Location: ../pages/admin_dashboard.php");
} else {
    header("Location: ../pages/dashboard.php");
}
exit;
