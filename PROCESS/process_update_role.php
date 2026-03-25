<?php
require_once __DIR__ . '/../HELPERS/auth.php';
require_once __DIR__ . '/../CONFIG/db.php';

require_login();

$u = auth_user();
if ($u['role'] !== 'admin') {
    header("Location: ../PAGES/booklist.php?msg=Access%20denied.%20Admins%20only.");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../PAGES/userlist.php");
    exit;
}

$target_id = (int) ($_POST['user_id'] ?? 0);
$new_role  = $_POST['role'] ?? '';

if (!in_array($new_role, ['admin', 'staff'], true)) {
    header("Location: ../PAGES/userlist.php?msg=Invalid%20role%20value.");
    exit;
}
if ($target_id === (int) $u['id']) {
    header("Location: ../PAGES/userlist.php?msg=You%20cannot%20change%20your%20own%20role.");
    exit;
}
if ($target_id < 1) {
    header("Location: ../PAGES/userlist.php?msg=Invalid%20user.");
    exit;
}

$stmt = $conn->prepare("UPDATE users SET role = ? WHERE id = ?");
$stmt->bind_param("si", $new_role, $target_id);
$ok = $stmt->execute();
$stmt->close();

if ($ok) {
    header("Location: ../PAGES/userlist.php?msg=Role%20updated%20successfully.");
} else {
    header("Location: ../PAGES/userlist.php?msg=Error%20updating%20role.");
}
exit;
?>