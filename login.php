<?php
require_once 'includes/header.php';
require_once 'includes/csrf.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$pdo = new PDO("mysql:host=localhost;dbname=dbtasks", "root", "");
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    
    if (!validate_csrf_token($_POST['csrf_token'] ?? '')) {
        die("בבקשה נסה שוב – טופס לא תקף.");
    }
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$_POST["username"]]);
    $user = $stmt->fetch();

    if ($user && password_verify($_POST["password"], $user["password"])) {
        $_SESSION["user_id"] = $user["id"];
        header("Location: index.php");
    } else {
        $error = "שם משתמש או סיסמה שגויים";
    }
}
?>

<head>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<div class="container mt-5">
    <h1 class="mb-4">ברוך הבא למערכת ניהול המשימות</h1>
    <p>מערכת זו מאפשרת לך להוסיף, לנהל ולסנן את המשימות שלך בקלות.</p>
</div>
<h2>התחברות</h2>
<form method="post">
    <input type="text" name="username" placeholder="שם משתמש" required>
    <input type="password" name="password" placeholder="סיסמה" required>
    <input type="hidden" name="csrf_token" value="<?= generate_csrf_token(); ?>">
    <button type="submit">התחבר</button>
    <a href="register.php" class="btn">להרשמה</a>
</form>
<?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>
<?php require_once 'includes/footer.php'; ?>
