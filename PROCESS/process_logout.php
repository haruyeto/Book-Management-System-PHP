<?php
require_once __DIR__ . '/../HELPERS/auth.php';

auth_logout();
header("Location: ../PAGES/login.php?msg=You%20have%20been%20logged%20out");
exit;
?>