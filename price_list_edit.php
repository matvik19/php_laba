<?php
include 'db.php';

$id = $_GET['id'];

// Получаем данные текущей записи
$stmt = $pdo->prepare("SELECT * FROM price_list WHERE price_list_id = ?");
$stmt->execute([$id]);
$price = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$price) {
    die("Запись не найдена.");
}

// Получаем список моделей автомобилей для выпадающего списка
$modelQuery = "SELECT model_id, model_name FROM car_models";
$modelStmt = $pdo->query($modelQuery);
$models = $modelStmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $model_id = $_POST['model_id'];
    $release_year = $_POST['release_year'];
    $car_price = $_POST['car_price'];
    $preparation_cost = $_POST['preparation_cost'];
    $transportation_cost = $_POST['transportation_cost'];

    $stmt = $pdo->prepare("UPDATE price_list SET model_id = ?, release_year = ?, car_price = ?, preparation_cost = ?, transportation_cost = ? WHERE price_list_id = ?");
    $stmt->execute([$model_id, $release_year, $car_price, $preparation_cost, $transportation_cost, $id]);

    header("Location: price_list_read.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Редактировать прайс-лист</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container">
    <h1 class="mt-5">Редактировать запись в прайс-листе</h1>
    <form method="POST">
        <div class="form-group">
            <label>Модель автомобиля:</label>
            <select name="model_id" class="form-control" required>
                <?php foreach ($models as $model): ?>
                    <option value="<?= $model['model_id'] ?>" <?= $model['model_id'] == $price['model_id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($model['model_name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label>Год выпуска:</label>
            <input type="number" name="release_year" value="<?= $price['release_year'] ?>" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Цена автомобиля:</label>
            <input type="number" step="0.01" name="car_price" value="<?= $price['car_price'] ?>" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Стоимость подготовки:</label>
            <input type="number" step="0.01" name="preparation_cost" value="<?= $price['preparation_cost'] ?>" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Стоимость транспортировки:</label>
            <input type="number" step="0.01" name="transportation_cost" value="<?= $price['transportation_cost'] ?>" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Сохранить</button>
        <a href="price_list_read.php" class="btn btn-secondary">Назад</a>
    </form>
</body>
</html>
