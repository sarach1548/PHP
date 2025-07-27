<?php
require_once 'includes/header.php';
require_once 'includes/csrf.php';

$pdo = new PDO("mysql:host=localhost;dbname=dbtasks", "root", "");
$error = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!validate_csrf_token($_POST['csrf_token'] ?? '')) {
        die("בבקשה נסה שוב – טופס לא תקף.");
    }
    $username = $_POST["username"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    try {
        $stmt->execute([$username, $password]);

        // התחברות אוטומטית לאחר הרשמה
        $user_id = $pdo->lastInsertId();
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['user_id'] = $user_id;

        header("Location: index.php");
        exit;
    } catch (PDOException $e) {
        $error = "שם משתמש כבר קיים";
    }
}
?>

<head>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<div class="container mt-5">
        <h1 class="mb-4">ברוך הבא למערכת ניהול המשימות</h1>
        <p>מערכת זו מאפשרת לך להוסיף, לנהל ולסנן את המשימות שלך בקלות.</p>
</div>
<h2>הרשמה</h2>
<form method="post">
    <?= csrf_input() ?>
    <input type="text" name="username" placeholder="שם משתמש" required>
    <input type="password" name="password" placeholder="סיסמה" required>
    <input type="hidden" name="csrf_token" value="<?= generate_csrf_token(); ?>">ס 
    <button type="submit">הירשם</button>
</form>

<?php require_once 'includes/footer.php'; ?>





