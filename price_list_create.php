<?php
include 'db.php';

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
    $current_year = date("Y");

    // Проверка года выпуска
    if ($release_year < 1900 || $release_year > $current_year) {
        die("Ошибка: Год выпуска должен быть между 1900 и $current_year.");
    }

    // Проверка числовых значений
    if ($car_price < 0 || $car_price > 99999999.99) {
        die("Ошибка: Цена автомобиля должна быть в пределах от 0 до 99999999.99.");
    }
    if ($preparation_cost < 0 || $preparation_cost > 99999999.99) {
        die("Ошибка: Стоимость подготовки должна быть в пределах от 0 до 99999999.99.");
    }
    if ($transportation_cost < 0 || $transportation_cost > 99999999.99) {
        die("Ошибка: Стоимость транспортировки должна быть в пределах от 0 до 99999999.99.");
    }

    try {
        $stmt = $pdo->prepare("INSERT INTO price_list (model_id, release_year, car_price, preparation_cost, transportation_cost) 
                               VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$model_id, $release_year, $car_price, $preparation_cost, $transportation_cost]);
        header("Location: price_list_read.php");
        exit;
    } catch (PDOException $e) {
        die("Ошибка: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Добавить цену</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container">
    <h1 class="mt-5">Добавить новую запись в прайс-лист</h1>
    <form method="POST">
        <div class="form-group">
            <label>Модель автомобиля:</label>
            <select name="model_id" class="form-control" required>
                <option value="">Выберите модель</option>
                <?php foreach ($models as $model): ?>
                    <option value="<?= $model['model_id'] ?>"><?= htmlspecialchars($model['model_name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label>Год выпуска:</label>
            <input type="number" name="release_year" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Цена автомобиля:</label>
            <input type="number" step="0.01" name="car_price" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Стоимость подготовки:</label>
            <input type="number" step="0.01" name="preparation_cost" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Стоимость транспортировки:</label>
            <input type="number" step="0.01" name="transportation_cost" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Добавить</button>
        <a href="price_list_read.php" class="btn btn-secondary">Назад</a>
    </form>
</body>
</html>
