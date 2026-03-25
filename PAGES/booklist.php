<?php
require_once __DIR__ . '/../HELPERS/functions.php';
require_once __DIR__ . '/../HELPERS/auth.php';
require_once __DIR__ . '/../CONFIG/db.php';
require_login();
$u = auth_user();
$result = $conn->query("SELECT * FROM books ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Inventory | BookShelf</title>
    <link rel="stylesheet" href="../ASSETS/style.css">
</head>
<body>
<div class="main-viewport">
    <header class="topbar">
        <a class="topbar-brand" href="booklist.php">BookShelf System</a>
        <div style="display:flex; align-items:center; gap:20px;">
            <?php if ($u['role'] === 'admin'): ?>
                <a href="userlist.php" style="color:white; text-decoration:none; font-size:0.9rem;">User Management</a>
            <?php endif; ?>
            <span style="font-size:0.9rem; opacity:0.8;"><?= e($u['full_name']) ?> (<?= e($u['role']) ?>)</span>
            <a href="../PROCESS/process_logout.php" class="btn btn-outline" style="color:white; border-color:rgba(255,255,255,0.3); padding:4px 12px;">Logout</a>
        </div>
    </header>

    <div class="content-center">
        <div class="formal-card wide">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.5rem;">
                <h2 style="margin:0;">Book Inventory</h2>
                <a href="add_book.php" class="btn btn-primary">Add Record</a>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Author</th>
                        <th>Genre</th>
                        <th>Price</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td>#<?= (int)$row['id'] ?></td>
                        <td><strong><?= e($row['title']) ?></strong></td>
                        <td><?= e($row['author']) ?></td>
                        <td><?= e($row['genre']) ?></td>
                        <td>₱<?= number_format((float)$row['price'], 2) ?></td>
                        <td>
                            <a href="edit_book.php?id=<?= (int)$row['id'] ?>" class="btn btn-outline" style="padding:4px 8px; font-size:0.75rem;">Edit</a>
                            <?php if ($u['role'] === 'admin'): ?>
                                <a href="../PROCESS/process_delete_book.php?id=<?= (int)$row['id'] ?>" class="btn" style="color:var(--danger); font-size:0.75rem;" onclick="return confirm('Delete record?')">Delete</a>
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