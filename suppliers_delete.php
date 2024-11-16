<?php
include 'db.php';

$id = $_GET['id'];

// Удаляем поставщика
$stmt = $pdo->prepare("DELETE FROM suppliers WHERE supplier_id = ?");
$stmt->execute([$id]);

header("Location: suppliers_read.php");
exit;
?>
