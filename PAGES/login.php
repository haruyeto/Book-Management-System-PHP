<?php
require_once __DIR__ . '/../HELPERS/functions.php';
require_once __DIR__ . '/../HELPERS/session.php';
if (!empty($_SESSION['user'])) { header("Location: booklist.php"); exit; }
$msg = e($_GET['msg'] ?? '');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login | BookShelf</title>
    <link rel="stylesheet" href="../ASSETS/style.css">
</head>
<body>
<div class="main-viewport">
    <div class="content-center">
        <div class="formal-card">
            <h1 style="text-align:center; margin:0 0 0.5rem 0;">System Login</h1>
            <p style="text-align:center; color:var(--text-muted); margin-bottom:2rem;">Authenticate to manage records.</p>
            
            <?php if ($msg !== ''): ?>
                <div style="color:var(--danger); background:#fef2f2; padding:10px; border-radius:4px; margin-bottom:1rem; font-size:0.85rem;">
                    <?= $msg ?>
                </div>
            <?php endif; ?>

            <form action="../PROCESS/process_login.php" method="POST">
                <div class="form-group">
                    <label class="form-label">Username</label>
                    <input class="form-control" type="text" name="username" required autofocus>
                </div>
                <div class="form-group">
                    <label class="form-label">Password</label>
                    <input class="form-control" type="password" name="password" required>
                </div>
                <button class="btn btn-primary" type="submit" style="width:100%; margin-top:1rem;">Sign In</button>
            </form>
            <p style="text-align:center; margin-top:1.5rem; font-size:0.85rem;">
                No account? <a href="register.php" style="color:var(--accent);">Register here</a>
            </p>
        </div>
    </div>
</div>
</body>
</html>