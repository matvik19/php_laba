<?php
include 'db.php';

$id = $_GET['id'];

// Удаляем запись из прайс-листа
$stmt = $pdo->prepare("DELETE FROM price_list WHERE price_list_id = ?");
$stmt->execute([$id]);

header("Location: price_list_read.php");
exit;
?>
