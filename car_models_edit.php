<?php
include 'db.php';

$id = $_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM car_models WHERE model_id = ?");
$stmt->execute([$id]);
$model = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$model) {
    die("Модель не найдена.");
}

$supplierQuery = "SELECT supplier_id, supplier_name FROM suppliers";
$supplierStmt = $pdo->query($supplierQuery);
$suppliers = $supplierStmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $model_name = $_POST['model_name'];
    $model_color = $_POST['model_color'];
    $model_upholstery = $_POST['model_upholstery'];
    $engine_power = $_POST['engine_power'];
    $door_count = $_POST['door_count'];
    $transmission_type = $_POST['transmission_type'];
    $supplier_id = $_POST['supplier_id'];

    $stmt = $pdo->prepare("UPDATE car_models SET model_name = ?, model_color = ?, model_upholstery = ?, engine_power = ?, door_count = ?, transmission_type = ?, supplier_id = ? WHERE model_id = ?");
    $stmt->execute([$model_name, $model_color, $model_upholstery, $engine_power, $door_count, $transmission_type, $supplier_id, $id]);

    header("Location: car_models_read.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Редактировать модель автомобиля</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container">
    <h1 class="mt-5">Редактировать модель автомобиля</h1>
    <form method="POST">
        <div class="form-group">
            <label>Название модели:</label>
            <input type="text" name="model_name" value="<?= $model['model_name'] ?>" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Цвет:</label>
            <input type="text" name="model_color" value="<?= $model['model_color'] ?>" class="form-control">
        </div>
        <div class="form-group">
            <label>Обивка:</label>
            <input type="text" name="model_upholstery" value="<?= $model['model_upholstery'] ?>" class="form-control">
        </div>
        <div class="form-group">
            <label>Мощность двигателя:</label>
            <input type="text" name="engine_power" value="<?= $model['engine_power'] ?>" class="form-control">
        </div>
        <div class="form-group">
            <label>Количество дверей:</label>
            <input type="number" name="door_count" value="<?= $model['door_count'] ?>" class="form-control">
        </div>
        <div class="form-group">
            <label>Тип трансмиссии:</label>
            <select name="transmission_type" class="form-control" required>
                <option value="Автоматическая" <?= $model['transmission_type'] == "Автоматическая" ? 'selected' : '' ?>>Автоматическая</option>
                <option value="Механическая" <?= $model['transmission_type'] == "Механическая" ? 'selected' : '' ?>>Механическая</option>
            </select>
        </div>
        <div class="form-group">
            <label>Поставщик:</label>
            <select name="supplier_id" class="form-control" required>
                <option value="">Выберите поставщика</option>
                <?php foreach ($suppliers as $supplier): ?>
                    <option value="<?= $supplier['supplier_id'] ?>" <?= $supplier['supplier_id'] == $model['supplier_id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($supplier['supplier_name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Сохранить</button>
        <a href="car_models_read.php" class="btn btn-secondary">Назад</a>
    </form>
</body>
</html>
