<?php
require_once __DIR__ . '/../HELPERS/functions.php';
require_once __DIR__ . '/../HELPERS/session.php';

if (!empty($_SESSION['user'])) {
    header("Location: booklist.php");
    exit;
}

$msg = e($_GET['msg'] ?? '');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account — BookShelf</title>
    <link rel="stylesheet" href="../ASSETS/style.css">
</head>
<body>
<div class="auth-wrap">
    <div class="auth-card" style="max-width:480px;">

        <div class="auth-logo">
            <div class="auth-logo-icon">📚</div>
            <div class="auth-logo-text">
                BookShelf
                <small>Management System</small>
            </div>
        </div>

        <h1 class="auth-title">Create Account</h1>
        <p class="auth-subtitle">Join the team and start managing books!</p>

        <?php if ($msg !== ''): ?>
            <div class="alert alert-error">
                <span class="alert-icon">⚠️</span>
                <span><?= $msg ?></span>
            </div>
        <?php endif; ?>

        <form action="../PROCESS/process_register.php" method="POST">
            <div class="form-group">
                <label class="form-label" for="full_name">
                    Full Name <span class="required">*</span>
                </label>
                <input
                    class="form-control"
                    type="text"
                    id="full_name"
                    name="full_name"
                    maxlength="120"
                    placeholder="e.g. Maria Santos"
                    required
                    autofocus
                >
            </div>

            <div class="form-group">
                <label class="form-label" for="username">
                    Username <span class="required">*</span>
                </label>
                <input
                    class="form-control"
                    type="text"
                    id="username"
                    name="username"
                    maxlength="60"
                    placeholder="e.g. maria_santos"
                    autocomplete="username"
                    required
                >
                <p style="font-size:0.75rem;color:var(--gray-400);margin-top:5px;font-weight:600;">
                    Letters, numbers, underscore (_) or dot (.) only.
                </p>
            </div>

            <div class="form-row">
                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label" for="password">
                        Password <span class="required">*</span>
                    </label>
                    <input
                        class="form-control"
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Min. 8 characters"
                        autocomplete="new-password"
                        required
                    >
                </div>

                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label" for="password_confirm">
                        Confirm Password <span class="required">*</span>
                    </label>
                    <input
                        class="form-control"
                        type="password"
                        id="password_confirm"
                        name="password_confirm"
                        placeholder="Re-enter password"
                        autocomplete="new-password"
                        required
                    >
                </div>
            </div>

            <div class="form-actions" style="margin-top:28px;">
                <button class="btn btn-primary" type="submit" style="width:100%;justify-content:center;">
                    ✨ Create My Account
                </button>
            </div>
        </form>

        <p class="auth-footer">
            Already have an account? <a href="login.php">Log in →</a>
        </p>
    </div>
</div>
</body>
</html>