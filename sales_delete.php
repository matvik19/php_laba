<?php
include 'db.php';

$id = $_GET['id'];

// Удаляем запись о продаже
$stmt = $pdo->prepare("DELETE FROM sales WHERE sale_id = ?");
$stmt->execute([$id]);

header("Location: sales_read.php");
exit;
?>
