<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $client_name = $_POST['client_full_name'];
    $contract_number = $_POST['contract_number'];
    $purchase_date = $_POST['purchase_date'];
    $client_phone = $_POST['client_phone'];
    $client_address = $_POST['client_address'];
    
    $stmt = $pdo->prepare("INSERT INTO clients (client_full_name, contract_number, purchase_date, client_phone, client_address) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$client_name, $contract_number, $purchase_date, $client_phone, $client_address]);

    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Добавить клиента</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container">
    <h1 class="mt-5">Добавить нового клиента</h1>
    <form method="POST">
        <div class="form-group">
            <label>Имя клиента:</label>
            <input type="text" name="client_full_name" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Номер контракта:</label>
            <input type="text" name="contract_number" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Дата покупки:</label>
            <input type="date" name="purchase_date" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Телефон клиента:</label>
            <input type="text" name="client_phone" class="form-control">
        </div>
        <div class="form-group">
            <label>Адрес клиента:</label>
            <input type="text" name="client_address" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Добавить</button>
        <a href="index.php" class="btn btn-secondary">Назад</a>
    </form>
</body>
</html>
