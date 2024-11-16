<?php
include 'db.php';

$id = $_GET['id'];

// Удаляем модель автомобиля
$stmt = $pdo->prepare("DELETE FROM car_models WHERE model_id = ?");
$stmt->execute([$id]);

header("Location: car_models_read.php");
exit;
?>
