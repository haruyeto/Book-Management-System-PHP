<?php
require_once __DIR__ . '/../HELPERS/functions.php';
require_once __DIR__ . '/../HELPERS/auth.php';
require_once __DIR__ . '/../CONFIG/db.php';

require_login();
$u = auth_user();

$id     = (int) ($_GET['id'] ?? 0);
$errors = [];

$stmt = $conn->prepare("SELECT * FROM books WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$book = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$book) {
    header("Location: booklist.php?msg=Book%20not%20found.");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title            = cleanInput($_POST['title'] ?? '');
    $author           = cleanInput($_POST['author'] ?? '');
    $genre            = cleanInput($_POST['genre'] ?? '');
    $publication_year = (int) ($_POST['publication_year'] ?? 0);
    $price            = $_POST['price'] ?? '';

    if ($title === '')                                         $errors[] = "Title cannot be empty.";
    if ($author === '')                                        $errors[] = "Author cannot be empty.";
    if ($genre === '')                                         $errors[] = "Genre cannot be empty.";
    if ($publication_year < 1000 || $publication_year > (int)date('Y'))
                                                               $errors[] = "Publication year must be between 1000 and " . date('Y') . ".";
    if (!is_numeric($price) || $price < 0)                    $errors[] = "Price must be a valid positive number.";
    if (is_numeric($price) && $price > 999999.99)             $errors[] = "Price cannot exceed ₱999,999.99.";

    if (empty($errors)) {
        $stmt = $conn->prepare(
            "UPDATE books SET title=?, author=?, genre=?, publication_year=?, price=? WHERE id=?"
        );
        $stmt->bind_param("sssidi", $title, $author, $genre, $publication_year, $price, $id);
        $stmt->execute();
        $stmt->close();
        header("Location: booklist.php?msg=Book%20updated%20successfully.");
        exit;
    }
    $book = array_merge($book, compact('title','author','genre','publication_year','price'));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Book — BookShelf</title>
    <link rel="stylesheet" href="../ASSETS/style.css">
</head>
<body>

<!-- TOPBAR -->
<header class="topbar">
    <a class="topbar-brand" href="booklist.php">
        <div class="brand-icon">📚</div>
        BookShelf
    </a>
    <div class="topbar-right">
        <div class="topbar-user">
            <div class="user-avatar"><?= strtoupper(substr($u['full_name'], 0, 1)) ?></div>
            <div>
                <strong><?= e($u['full_name']) ?></strong>
                <span><?= e($u['role']) ?></span>
            </div>
        </div>
        <a class="nav-link danger" href="../PROCESS/process_logout.php"
           onclick="return confirm('Log out?')">🚪 Logout</a>
    </div>
</header>

<div class="page-wrapper">

    <div class="page-header">
        <h1 class="page-title">Edit <span>Book</span></h1>
        <p class="page-subtitle">Update details for Book <span class="id-chip">#<?= (int)$book['id'] ?></span></p>
    </div>

    <a class="btn btn-ghost btn-sm" href="booklist.php" style="margin-bottom:28px;display:inline-flex;">
        ← Back to Book List
    </a>

    <div class="form-card wide">

        <?php if (!empty($errors)): ?>
            <div class="alert alert-error">
                <span class="alert-icon">⚠️</span>
                <div>
                    <strong>Please fix the following:</strong>
                    <ul><?php foreach ($errors as $err): ?><li><?= e($err) ?></li><?php endforeach; ?></ul>
                </div>
            </div>
        <?php endif; ?>

        <form method="POST" action="">

            <div class="form-group">
                <label class="form-label" for="title">Book Title <span class="required">*</span></label>
                <input class="form-control" type="text" id="title" name="title"
                    value="<?= e($book['title']) ?>"
                    placeholder="e.g. The Great Gatsby"
                    required autofocus>
            </div>

            <div class="form-group">
                <label class="form-label" for="author">Author <span class="required">*</span></label>
                <input class="form-control" type="text" id="author" name="author"
                    value="<?= e($book['author']) ?>"
                    placeholder="e.g. F. Scott Fitzgerald"
                    required>
            </div>

            <div class="form-group">
                <label class="form-label" for="genre">Genre <span class="required">*</span></label>
                <input class="form-control" type="text" id="genre" name="genre"
                    value="<?= e($book['genre']) ?>"
                    placeholder="e.g. Classic, Fiction, Technology"
                    required>
            </div>

            <div class="form-row">
                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label" for="publication_year">Publication Year <span class="required">*</span></label>
                    <input class="form-control" type="number" id="publication_year" name="publication_year"
                        value="<?= (int)$book['publication_year'] ?>"
                        placeholder="e.g. 1999"
                        min="1000" max="<?= date('Y') ?>" required>
                </div>

                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label" for="price">Price (₱) <span class="required">*</span></label>
                    <input class="form-control" type="number" id="price" name="price"
                        value="<?= e((string)$book['price']) ?>"
                        placeholder="e.g. 299.00"
                        step="0.01" min="0" max="999999.99" required>
                </div>
            </div>

            <div class="form-actions">
                <button class="btn btn-primary" type="submit">💾 Update Book</button>
                <a class="btn btn-ghost" href="booklist.php">Cancel</a>
            </div>

        </form>
    </div>
<body>
<div class="main-viewport">
    <div class="content-center">
        <div class="formal-card" style="max-width:600px;">
            <h2 style="margin-top:0;">Add Record</h2>
            <form method="POST" action="">
                <div class="form-group">
                    <label class="form-label">Book Title</label>
                    <input class="form-control" type="text" name="title" required>
                </div>
                <div style="display:flex; gap:10px; margin-top:1.5rem;">
                    <button class="btn btn-primary" type="submit" style="flex:1;">Save Record</button>
                    <a href="booklist.php" class="btn btn-outline" style="flex:1;">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</div>
</body>
</html>