<?php
require_once __DIR__ . '/../HELPERS/functions.php';
require_once __DIR__ . '/../HELPERS/auth.php';
require_once __DIR__ . '/../CONFIG/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../PAGES/register.php?msg=Invalid%20request");
    exit;
}

$full_name = cleanInput($_POST['full_name'] ?? '');
$username  = strtolower(cleanInput($_POST['username'] ?? ''));
$password  = $_POST['password'] ?? '';
$confirm   = $_POST['password_confirm'] ?? '';
$role      = 'staff';

if ($full_name === '' || $username === '' || $password === '' || $confirm === '') {
    header("Location: ../PAGES/register.php?msg=Please%20complete%20all%20fields");
    exit;
}
if ($password !== $confirm) {
    header("Location: ../PAGES/register.php?msg=Passwords%20do%20not%20match");
    exit;
}
if (strlen($password) < 8) {
    header("Location: ../PAGES/register.php?msg=Password%20must%20be%20at%20least%208%20characters");
    exit;
}
if (!preg_match('/^[a-z0-9\_\.]{3,60}$/i', $username)) {
    header("Location: ../PAGES/register.php?msg=Username%20can%20only%20contain%20letters%2C%20numbers%2C%20_%20or%20.");
    exit;
}

$stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$exists = $stmt->get_result()->fetch_assoc();
$stmt->close();

if ($exists) {
    header("Location: ../PAGES/register.php?msg=Username%20already%20exists");
    exit;
}

$hash = password_hash($password, PASSWORD_DEFAULT);
$stmt = $conn->prepare("INSERT INTO users (username, password_hash, full_name, role) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $username, $hash, $full_name, $role);
$ok    = $stmt->execute();
$newId = $stmt->insert_id;
$stmt->close();

if (!$ok) {
    header("Location: ../PAGES/register.php?msg=Unable%20to%20create%20account");
    exit;
}

$user = [
    'id'        => (int) $newId,
    'username'  => $username,
    'full_name' => $full_name,
    'role'      => $role,
];

auth_login($user);
header("Location: ../PAGES/booklist.php?msg=Welcome!");
exit;
?>