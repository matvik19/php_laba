<?php
include 'db.php';

$query = "SELECT * FROM suppliers";
$stmt = $pdo->query($query);
$suppliers = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Поставщики</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
</head>
<body class="container">
    <h1 class="mt-5">Поставщики</h1>
    <a href="suppliers_create.php" class="btn btn-primary mb-3">Добавить поставщика</a>
    <a href="index.php" class="btn btn-secondary mb-3">Назад на главную</a>

    <table class="table table-bordered">
        <thead class="thead-white">
            <tr>
                <th>Название поставщика</th>
                <th>Телефон</th>
                <th>Email</th>
                <th>Вебсайт</th>
                <th>Адрес</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($suppliers as $supplier): ?>
                <tr>
                    <td><?= $supplier['supplier_name'] ?></td>
                    <td><?= $supplier['supplier_phone'] ?></td>
                    <td><?= $supplier['supplier_email'] ?></td>
                    <td><?= $supplier['supplier_website'] ?></td>
                    <td><?= $supplier['supplier_address'] ?></td>
                    <td>
                        <a href="suppliers_edit.php?id=<?= $supplier['supplier_id'] ?>" class="btn btn-sm btn" title="Изменить">
                            <i class="fas fa-edit"></i>
                        </a>
                        <a href="suppliers_delete.php?id=<?= $supplier['supplier_id'] ?>" class="btn btn-sm btn" onclick="return confirm('Удалить поставщика?')" title="Удалить">
                            <i class="fas fa-trash-alt"></i>
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
