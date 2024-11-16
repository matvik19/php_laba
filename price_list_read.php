<?php
include 'db.php';

$query = "SELECT price_list.price_list_id, car_models.model_name, price_list.release_year, 
                  price_list.car_price, price_list.preparation_cost, price_list.transportation_cost 
           FROM price_list
           LEFT JOIN car_models ON price_list.model_id = car_models.model_id";
$stmt = $pdo->query($query);
$prices = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Прайс-лист</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
</head>
<body class="container">
    <h1 class="mt-5">Прайс-лист</h1>
    <a href="price_list_create.php" class="btn btn-primary mb-3">Добавить запись</a>
    <a href="index.php" class="btn btn-secondary mb-3">Назад на главную</a>

    <table class="table table-bordered">
        <thead class="thead-white">
            <tr>
                <th>Модель автомобиля</th>
                <th>Год выпуска</th>
                <th>Цена автомобиля</th>
                <th>Стоимость подготовки</th>
                <th>Стоимость транспортировки</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($prices as $price): ?>
                <tr>
                    <td><?= $price['model_name'] ?></td>
                    <td><?= $price['release_year'] ?></td>
                    <td><?= $price['car_price'] ?></td>
                    <td><?= $price['preparation_cost'] ?></td>
                    <td><?= $price['transportation_cost'] ?></td>
                    <td>
                        <a href="price_list_edit.php?id=<?= $price['price_list_id'] ?>" class="btn btn-sm btn" title="Изменить">
                            <i class="fas fa-edit"></i>
                        </a>
                        <a href="price_list_delete.php?id=<?= $price['price_list_id'] ?>" class="btn btn-sm btn" onclick="return confirm('Удалить запись?')" title="Удалить">
                            <i class="fas fa-trash-alt"></i>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
