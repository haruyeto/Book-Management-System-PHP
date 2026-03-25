<?php
require_once __DIR__ . '/../HELPERS/functions.php';
require_once __DIR__ . '/../HELPERS/auth.php';
require_once __DIR__ . '/../CONFIG/db.php';
require_login();
$u = auth_user();
if ($u['role'] !== 'admin') { header("Location: booklist.php"); exit; }
$result = $conn->query("SELECT * FROM users ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Users | BookShelf</title>
    <link rel="stylesheet" href="../ASSETS/style.css">
</head>
<body>
<div class="main-viewport">
    <header class="topbar">
        <a class="topbar-brand" href="booklist.php">BookShelf System</a>
        <a href="booklist.php" style="color:white; text-decoration:none; font-size:0.9rem;">Back to Inventory</a>
    </header>

    <div class="content-center">
        <div class="formal-card wide">
            <h2 style="margin-bottom:1.5rem;">User Management</h2>
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Username</th>
                        <th>Role</th>
                        <th>Registered</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= e($row['full_name']) ?></td>
                        <td>@<?= e($row['username']) ?></td>
                        <td><span style="text-transform:uppercase; font-size:0.7rem; font-weight:bold;"><?= e($row['role']) ?></span></td>
                        <td><?= date('Y-m-d', strtotime($row['created_at'])) ?></td>
                        <td>
                            <?php if ((int)$row['id'] !== (int)$u['id']): ?>
                                <form method="POST" action="../PROCESS/process_update_role.php" style="display:flex; gap:5px;">
                                    <input type="hidden" name="user_id" value="<?= $row['id'] ?>">
                                    <select name="role" class="form-control" style="padding:2px; width:auto;">
                                        <option value="admin" <?= $row['role']==='admin'?'selected':'' ?>>Admin</option>
                                        <option value="staff" <?= $row['role']==='staff'?'selected':'' ?>>Staff</option>
                                    </select>
                                    <button type="submit" class="btn btn-outline" style="padding:2px 8px;">Update</button>
                                </form>
                            <?php else: ?>
                                <small style="color:var(--text-muted);">Self</small>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>