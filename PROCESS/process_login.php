<?php
require_once __DIR__ . '/../HELPERS/functions.php';
require_once __DIR__ . '/../HELPERS/auth.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../PAGES/login.php?msg=Invalid%20request");
    exit;
}

$username = strtolower(cleanInput($_POST['username'] ?? ''));
$password = $_POST['password'] ?? '';

if ($username === '' || $password === '') {
    header("Location: ../PAGES/login.php?msg=Please%20enter%20username%20and%20password");
    exit;
}

$user = auth_attempt($username, $password);

if (!$user) {
    header("Location: ../PAGES/login.php?msg=Invalid%20credentials");
    exit;
}

auth_login($user);
header("Location: ../PAGES/booklist.php");
exit;
?>