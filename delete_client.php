<?php
include 'db.php';

$id = $_GET['id'];
$stmt = $pdo->prepare("DELETE FROM clients WHERE client_id = ?");
$stmt->execute([$id]);

header("Location: index.php");
exit;
?>
