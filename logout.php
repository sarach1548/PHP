<?php
session_start();
session_unset();  // מוחק את כל המשתנים מה-Session
session_destroy(); // סוגר את הסשן

header("Location: login.php"); // מפנה למסך התחברות
exit;
