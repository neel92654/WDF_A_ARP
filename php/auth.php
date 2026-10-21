<?php
require_once __DIR__ . '/db.php';

function start_session_safe() {
    if (session_status() === PHP_SESSION_NONE) {
        ini_set('session.cookie_httponly', '1');
        ini_set('session.use_only_cookies', '1');
        session_start();
    }
}

function login_user($user) {
    start_session_safe();
    session_regenerate_id(true);
    $_SESSION['user_id'] = (int)$user['id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['email'] = $user['email'];
    $_SESSION['role'] = $user['role'];
    $_SESSION['last_activity'] = time();
}

function logout_user() {
    start_session_safe();
    if (isset($_SESSION['user_id'])) {
        $userId = (int)$_SESSION['user_id'];
        $conn = get_db_connection();
        $stmt = mysqli_prepare($conn, "UPDATE users SET remember_token_hash = NULL, remember_token_expires_at = NULL WHERE id = ?");
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "i", $userId);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }
    }
    if (isset($_COOKIE['remember_token'])) {
        setcookie('remember_token', '', time() - 42000, '/');
    }
    $_SESSION = [];
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    session_destroy();
}

function check_auth($requiredRole = null) {
    start_session_safe();

    if (!isset($_SESSION['user_id'])) {
        if (!empty($_COOKIE['remember_token'])) {
            $conn = get_db_connection();
            $tokenHash = hash('sha256', $_COOKIE['remember_token']);
            $stmt = mysqli_prepare($conn, "SELECT id, username, email, role FROM users WHERE remember_token_hash = ? AND remember_token_expires_at > NOW() LIMIT 1");
            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "s", $tokenHash);
                mysqli_stmt_execute($stmt);
                $res = mysqli_stmt_get_result($stmt);
                if ($u = mysqli_fetch_assoc($res)) {
                    login_user($u);
                }
                mysqli_stmt_close($stmt);
            }
        }
    }

    if (!isset($_SESSION['user_id'])) {
        header("Location: ../pages/login.html?msg=unauthorized");
        exit;
    }

    $timeoutSeconds = 1800;
    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $timeoutSeconds)) {
        logout_user();
        header("Location: ../pages/login.html?msg=timeout");
        exit;
    }
    $_SESSION['last_activity'] = time();

    if ($requiredRole !== null) {
        if ($_SESSION['role'] !== $requiredRole) {
            if ($_SESSION['role'] === 'student') {
                header("Location: ../pages/dashboard.php?msg=forbidden");
            } else {
                header("Location: ../pages/admin_dashboard.php?msg=forbidden");
            }
            exit;
        }
    }

    return $_SESSION;
}

function is_logged_in() {
    start_session_safe();
    if (!isset($_SESSION['user_id'])) {
        return false;
    }
    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 1800)) {
        logout_user();
        return false;
    }
    return true;
}

function get_current_user_role() {
    start_session_safe();
    return $_SESSION['role'] ?? null;
}
