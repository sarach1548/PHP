<!-- 📄 includes/header.php -->
<?php
require_once 'db.php';
require_once 'session.php';

$user = null;
if (isset($_SESSION['user_id'])) {
    $stmt = $pdo->prepare("SELECT username FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch();
}
?>
<!DOCTYPE html>
<html lang="he" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ניהול משימות</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php">
            <img src="assets/img/note.webp" alt="לוגו" width="40" height="40" class="d-inline-block align-text-top">
            ניהול משימות
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">המשימות שלי</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="add_task.php">הוסף משימה</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="about.php">אודות</a>
                </li>
            </ul>
            <span class="navbar-text">
                <?php if ($user): ?>
                    שלום, <?= htmlspecialchars($user['username']) ?> | <a href="logout.php">התנתק</a>
                <?php else: ?>
                    <a href="login.php">התחברות</a> / <a href="register.php">הרשמה</a>
                <?php endif; ?>
            </span>
        </div>
    </div>
</nav>

<!-- תוכן הדף מתחיל כאן -->
