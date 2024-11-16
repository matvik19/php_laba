<?php
include 'db.php';

$query = "SELECT sales.sale_id, clients.client_full_name, car_models.model_name, sales.sale_date, sales.contract_number 
          FROM sales
          LEFT JOIN clients ON sales.client_id = clients.client_id
          LEFT JOIN car_models ON sales.model_id = car_models.model_id";
$stmt = $pdo->query($query);
$sales = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Продажи</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
</head>
<body class="container">
    <h1 class="mt-5">Продажи</h1>
    <a href="sales_create.php" class="btn btn-primary mb-3">Добавить продажу</a>
    <a href="index.php" class="btn btn-secondary mb-3">Назад на главную</a>

    <table class="table table-bordered">
        <thead class="thead-white">
            <tr>
                <th>Клиент</th>
                <th>Модель автомобиля</th>
                <th>Дата продажи</th>
                <th>Номер договора</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($sales as $sale): ?>
                <tr>
                    <td><?= $sale['client_full_name'] ?></td>
                    <td><?= $sale['model_name'] ?></td>
                    <td><?= $sale['sale_date'] ?></td>
                    <td><?= $sale['contract_number'] ?></td>
                    <td>
                        <a href="sales_edit.php?id=<?= $sale['sale_id'] ?>" class="btn btn-sm btn" title="Изменить">
                            <i class="fas fa-edit"></i>
                        </a>
                        <a href="sales_delete.php?id=<?= $sale['sale_id'] ?>" class="btn btn-sm btn" onclick="return confirm('Удалить продажу?')" title="Удалить">
                            <i class="fas fa-trash-alt"></i>
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
