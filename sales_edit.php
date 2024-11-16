<?php
include 'db.php';

$id = $_GET['id'];

// Получаем данные о продаже
$stmt = $pdo->prepare("SELECT * FROM sales WHERE sale_id = ?");
$stmt->execute([$id]);
$sale = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$sale) {
    die("Продажа не найдена.");
}

// Получаем список клиентов
$clientsQuery = "SELECT client_id, client_full_name FROM clients";
$clientsStmt = $pdo->query($clientsQuery);
$clients = $clientsStmt->fetchAll(PDO::FETCH_ASSOC);

// Получаем список моделей автомобилей
$modelsQuery = "SELECT model_id, model_name FROM car_models";
$modelsStmt = $pdo->query($modelsQuery);
$models = $modelsStmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $client_id = $_POST['client_id'];
    $model_id = $_POST['model_id'];
    $sale_date = $_POST['sale_date'];
    $contract_number = $_POST['contract_number'];

    $stmt = $pdo->prepare("UPDATE sales SET client_id = ?, model_id = ?, sale_date = ?, contract_number = ? WHERE sale_id = ?");
    $stmt->execute([$client_id, $model_id, $sale_date, $contract_number, $id]);

    header("Location: sales_read.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Редактировать продажу</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container">
    <h1 class="mt-5">Редактировать продажу</h1>
    <form method="POST">
        <div class="form-group">
            <label>Клиент:</label>
            <select name="client_id" class="form-control" required>
                <?php foreach ($clients as $client): ?>
                    <option value="<?= $client['client_id'] ?>" <?= $client['client_id'] == $sale['client_id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($client['client_full_name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label>Модель автомобиля:</label>
            <select name="model_id" class="form-control" required>
                <?php foreach ($models as $model): ?>
                    <option value="<?= $model['model_id'] ?>" <?= $model['model_id'] == $sale['model_id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($model['model_name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label>Дата продажи:</label>
            <input type="date" name="sale_date" value="<?= $sale['sale_date'] ?>" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Номер договора:</label>
            <input type="text" name="contract_number" value="<?= $sale['contract_number'] ?>" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Сохранить</button>
        <a href="sales_read.php" class="btn btn-secondary">Назад</a>
    </form>
</body>
</html>
