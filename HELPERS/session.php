<?php
if (session_status() === PHP_SESSION_NONE) {
    $secure   = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
    $httponly = true;
    $samesite = 'Lax';

    session_set_cookie_params([
        'lifetime' => 0,
        'path'     => '/',
        'domain'   => '',
        'secure'   => $secure,
        'httponly' => $httponly,
        'samesite' => $samesite,
    ]);

    session_start();
}

function session_set(string $key, $value): void {
    $_SESSION[$key] = $value;
}

function session_get(string $key, $default = null) {
    return $_SESSION[$key] ?? $default;
}

function session_forget(string $key): void {
    unset($_SESSION[$key]);
}

function session_regenerate(): void {
    session_regenerate_id(true);
}
?>