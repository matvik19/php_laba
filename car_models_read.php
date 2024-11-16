<?php
include 'db.php';

$query = "SELECT car_models.model_id, car_models.model_name, car_models.model_color, 
                  car_models.model_upholstery, car_models.engine_power, car_models.door_count, 
                  car_models.transmission_type, suppliers.supplier_name 
           FROM car_models
           LEFT JOIN suppliers ON car_models.supplier_id = suppliers.supplier_id";
$stmt = $pdo->query($query);
$car_models = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Модели автомобилей</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
</head>
<body class="container">
    <h1 class="mt-5">Модели автомобилей</h1>
    <a href="car_models_create.php" class="btn btn-primary mb-3">Добавить модель автомобиля</a>
    <a href="index.php" class="btn btn-secondary mb-3">Назад на главную</a>

    <table class="table table-bordered">
        <thead class="thead-white">
            <tr>
                <th>Название модели</th>
                <th>Цвет</th>
                <th>Обивка</th>
                <th>Мощность двигателя</th>
                <th>Количество дверей</th>
                <th>Тип трансмиссии</th>
                <th>Поставщик</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($car_models as $model): ?>
                <tr>
                    <td><?= $model['model_name'] ?></td>
                    <td><?= $model['model_color'] ?></td>
                    <td><?= $model['model_upholstery'] ?></td>
                    <td><?= $model['engine_power'] ?></td>
                    <td><?= $model['door_count'] ?></td>
                    <td><?= $model['transmission_type'] ?></td>
                    <td><?= $model['supplier_name'] ?></td>
                    <td>
                        <a href="car_models_edit.php?id=<?= $model['model_id'] ?>" class="btn btn-sm btn" title="Изменить">
                            <i class="fas fa-edit"></i>
                        </a>
                        <a href="car_models_delete.php?id=<?= $model['model_id'] ?>" class="btn btn-sm btn" onclick="return confirm('Удалить модель?')" title="Удалить">
                            <i class="fas fa-trash-alt"></i>
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
