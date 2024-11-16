<?php

// Запрос для получения всех данных из таблицы `clients`
$query = "SELECT client_id, client_full_name, contract_number, purchase_date, client_phone, client_address FROM clients";
$stmt = $pdo->query($query);
$clients = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Клиенты</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
</head>
<body class="container">
    <h1 class="mt-5">Список клиентов</h1>

    <div class="mb-4">
        <a href="suppliers_read.php" class="btn btn-secondary">Поставщики</a>
        <a href="car_models_read.php" class="btn btn-secondary">Модели автомобилей</a>
        <a href="price_list_read.php" class="btn btn-secondary">Прейскурант цен</a>
        <a href="sales_read.php" class="btn btn-secondary">Продажи</a>
    </div>

    <table class="table table-bordered">
        <thead class="thead-white">
            <tr>
                <th>Имя Клиента</th>
                <th>Номер Контракта</th>
                <th>Дата Покупки</th>
                <th>Телефон</th>
                <th>Адрес</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($clients as $client): ?>
                <tr>
                    <td><?= $client['client_full_name'] ?></td>
                    <td><?= $client['contract_number'] ?></td>
                    <td><?= $client['purchase_date'] ?></td>
                    <td><?= $client['client_phone'] ?></td>
                    <td><?= $client['client_address'] ?></td>
                    <td>
                        <a href="edit_client.php?id=<?= $client['client_id'] ?>" class="btn btn-sm btn" title="Изменить">
                            <i class="fas fa-edit"></i> 
                        </a>
                        <a href="delete_client.php?id=<?= $client['client_id'] ?>" class="btn btn-sm btn" onclick="return confirm('Удалить клиента?')" title="Удалить">
                            <i class="fas fa-trash-alt"></i>
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <a href="create_client.php" class="btn btn-primary mt-3">Добавить клиента</a>
</body>
</html>
