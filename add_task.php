<!-- 📄 index.php -->
<?php
require_once 'includes/header.php';
require 'includes/db.php';
require 'includes/auth.php'; // כבר כולל session_start()
require_once 'includes/csrf.php';



// הוספת משימה
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty(trim($_POST['task']))) {
    if (!validate_csrf_token($_POST['csrf_token'] ?? '')) {
        die("בבקשה נסה שוב – טופס לא תקף.");
    }
    $task = trim($_POST['task']);
    $user_id = $_SESSION['user_id']; // מזהה המשתמש המחובר

    // מניעת כפילויות אצל אותו משתמש
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM tasks WHERE content = ? AND user_id = ?");
    $stmt->execute([$task, $user_id]);

    if ($stmt->fetchColumn() == 0) {
        $stmt = $pdo->prepare("INSERT INTO tasks (content, user_id) VALUES (?, ?)");
        $stmt->execute([$task, $user_id]);
    }

    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// מחיקת משימה
if (isset($_GET['delete'])) {
    $id = (int) $_GET['delete'];
    $pdo->prepare("DELETE FROM tasks WHERE id = ?")->execute([$id]);
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// סימון בוצע
if (isset($_GET['toggle'])) {
    $id = (int) $_GET['toggle'];
    $pdo->prepare("UPDATE tasks SET is_done = 1 - is_done WHERE id = ?")->execute([$id]);
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// הבאת כל המשימות לפי משתמש
$stmt = $pdo->prepare("SELECT * FROM tasks WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$tasks = $stmt->fetchAll();

// הבאת כל המשימות
// $tasks = $pdo->query("SELECT * FROM tasks ORDER BY id DESC")->fetchAll();
?>

<!DOCTYPE html>
<html lang="he">
<head>
    <meta charset="UTF-8">
    <title>רשימת משימות</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css?v=2"> <!-- אם יש לך CSS -->
</head>
<body>
 <main class="container mt-5">
    
    <h1>רשימת משימות</h1>
    <form method="post">
        <?= csrf_input() ?>
        <input type="text" name="task" placeholder="הוספת משימה" required>
        <input type="hidden" name="csrf_token" value="<?= generate_csrf_token(); ?>">
        <button type="submit">הוספה</button>
    </form>
    <ul>
        <?php foreach ($tasks as $task): ?>
            <li>
                <form method="get" style="display:inline">
                    <button name="toggle" value="<?= $task['id'] ?>" style="background-color:<?= $task['is_done'] ? '#8bc34a' : '#e0e0e0' ?>">
                        <?= $task['is_done'] ? '✅ בוצע' : '✔️ סמן' ?>
                    </button>
                </form>
                <?= htmlspecialchars($task['content']) ?>
                <form method="get" style="display:inline">
                    <button name="delete" value="<?= $task['id'] ?>" style="background-color:#f44336; color:white;">🗑️</button>
                </form>
            </li>
        <?php endforeach; ?>
    </ul>

    </main > 
</body>
</html>

<?php require_once 'includes/footer.php'; ?>

