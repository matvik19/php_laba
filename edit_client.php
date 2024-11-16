<?php
include 'db.php';

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT client_full_name, contract_number, purchase_date, client_phone, client_address FROM clients WHERE client_id = ?");
$stmt->execute([$id]);
$client = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $client_name = $_POST['client_full_name'];
    $contract_number = $_POST['contract_number'];
    $purchase_date = $_POST['purchase_date'];
    $client_phone = $_POST['client_phone'];
    $client_address = $_POST['client_address'];
    
    $updateStmt = $pdo->prepare("UPDATE clients SET client_full_name = ?, contract_number = ?, purchase_date = ?, client_phone = ?, client_address = ? WHERE client_id = ?");
    $updateStmt->execute([$client_name, $contract_number, $purchase_date, $client_phone, $client_address, $id]);

    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Изменить клиента</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container">
    <h1 class="mt-5">Изменить клиента</h1>
    <form method="POST">
        <div class="form-group">
            <label>Имя клиента:</label>
            <input type="text" name="client_full_name" value="<?= htmlspecialchars($client['client_full_name']) ?>" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Номер контракта:</label>
            <input type="text" name="contract_number" value="<?= htmlspecialchars($client['contract_number']) ?>" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Дата покупки:</label>
            <input type="date" name="purchase_date" value="<?= htmlspecialchars($client['purchase_date']) ?>" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Телефон клиента:</label>
            <input type="text" name="client_phone" value="<?= htmlspecialchars($client['client_phone']) ?>" class="form-control">
        </div>
        <div class="form-group">
            <label>Адрес клиента:</label>
            <input type="text" name="client_address" value="<?= htmlspecialchars($client['client_address']) ?>" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Сохранить</button>
        <a href="index.php" class="btn btn-secondary">Назад</a>
    </form>
</body>
</html>
