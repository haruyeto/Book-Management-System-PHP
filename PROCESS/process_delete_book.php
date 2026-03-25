<?php
require_once __DIR__ . '/../HELPERS/auth.php';
require_once __DIR__ . '/../CONFIG/db.php';

require_login();

$u = auth_user();
if ($u['role'] !== 'admin') {
    header("Location: ../PAGES/booklist.php?msg=Access%20denied.%20Only%20admins%20can%20delete%20records.");
    exit;
}

$id = (int) ($_GET['id'] ?? 0);
if ($id < 1) {
    header("Location: ../PAGES/booklist.php");
    exit;
}

$stmt = $conn->prepare("DELETE FROM books WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->close();

header("Location: ../PAGES/booklist.php?msg=Book%20deleted%20successfully.");
exit;
?>