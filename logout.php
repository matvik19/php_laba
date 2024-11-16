<?php
// logout.php
include 'db.php';
session_start();

if (isset($_SESSION['user'])) {
    // Очистка токена "Запомнить меня"
    $stmt = $pdo->prepare("UPDATE users SET remember_token = NULL WHERE user_id = :user_id");
    $stmt->execute(['user_id' => $_SESSION['user']['user_id']]);
}

$_SESSION = [];
session_destroy();

// Очистка куки
setcookie('remember_me', '', time() - 3600, "/");

header("Location: index.php");
exit;
?>
