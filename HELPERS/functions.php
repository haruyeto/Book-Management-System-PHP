<?php
function cleanInput(string $value): string {
    $value = trim($value);
    return preg_replace('/\s+/', ' ', $value);
}

function isValidEmail(string $email): bool {
    return (bool) filter_var($email, FILTER_VALIDATE_EMAIL);
}

function e(string $value): string {
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}
?>