<?php
require_once __DIR__ . '/../CONFIG/db.php';
require_once __DIR__ . '/session.php';

function auth_attempt(string $username, string $password): ?array {
    global $conn;

    $stmt = $conn->prepare(
        "SELECT id, username, password_hash, full_name, role FROM users WHERE username = ?"
    );
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if ($row && password_verify($password, $row['password_hash'])) {
        if (password_needs_rehash($row['password_hash'], PASSWORD_DEFAULT)) {
            $new = password_hash($password, PASSWORD_DEFAULT);
            $uid = (int) $row['id'];
            $upd = $conn->prepare("UPDATE users SET password_hash = ? WHERE id = ?");
            $upd->bind_param("si", $new, $uid);
            $upd->execute();
            $upd->close();
        }
        return [
            'id'        => (int) $row['id'],
            'username'  => $row['username'],
            'full_name' => $row['full_name'],
            'role'      => $row['role'],
        ];
    }
    return null;
}

function auth_login(array $user): void {
    session_regenerate();
    session_set('user', $user);
}

function auth_logout(): void {
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(), '', time() - 42000,
            $params['path'], $params['domain'],
            $params['secure'], $params['httponly']
        );
    }
    session_regenerate_id(true);
    session_destroy();
}

function auth_user(): ?array {
    return session_get('user');
}

function auth_is_logged_in(): bool {
    return (bool) auth_user();
}

function require_login(): void {
    if (!auth_is_logged_in()) {
        header("Location: ../PAGES/login.php?msg=Please%20login%20first");
        exit;
    }
}

function require_role(string $role): void {
    $u = auth_user();
    if (!$u || $u['role'] !== $role) {
        header("Location: ../PAGES/booklist.php?msg=Access%20denied.%20Admins%20only.");
        exit;
    }
}
?>