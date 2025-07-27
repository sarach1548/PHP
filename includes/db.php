<?php
$host = 'localhost';
$db   = 'DBTASKS';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // הצגת שגיאות
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // תוצאה כמערך אסוציאטיבי
    PDO::ATTR_EMULATE_PREPARES   => false,                  // מניעת הכנות מדומות
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    die("שגיאת חיבור: " . $e->getMessage());
}
?>
