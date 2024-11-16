<?php
include 'db.php';

$id = $_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM suppliers WHERE supplier_id = ?");
$stmt->execute([$id]);
$supplier = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$supplier) {
    die("Поставщик не найден.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $supplier_name = $_POST['supplier_name'];
    $supplier_phone = $_POST['supplier_phone'];
    $supplier_email = $_POST['supplier_email'];
    $supplier_website = $_POST['supplier_website'];
    $supplier_address = $_POST['supplier_address'];

    $stmt = $pdo->prepare("UPDATE suppliers SET supplier_name = ?, supplier_phone = ?, supplier_email = ?, supplier_website = ?, supplier_address = ? WHERE supplier_id = ?");
    $stmt->execute([$supplier_name, $supplier_phone, $supplier_email, $supplier_website, $supplier_address, $id]);

    header("Location: suppliers_read.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Редактировать поставщика</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container">
    <h1 class="mt-5">Редактировать поставщика</h1>
    <form method="POST">
        <div class="form-group">
            <label>Название поставщика:</label>
            <input type="text" name="supplier_name" value="<?= $supplier['supplier_name'] ?>" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Телефон:</label>
            <input type="text" name="supplier_phone" value="<?= $supplier['supplier_phone'] ?>" class="form-control">
        </div>
        <div class="form-group">
            <label>Email:</label>
            <input type="email" name="supplier_email" value="<?= $supplier['supplier_email'] ?>" class="form-control">
        </div>
        <div class="form-group">
            <label>Вебсайт:</label>
            <input type="url" name="supplier_website" value="<?= $supplier['supplier_website'] ?>" class="form-control">
        </div>
        <div class="form-group">
            <label>Адрес:</label>
            <textarea name="supplier_address" class="form-control"><?= $supplier['supplier_address'] ?></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Сохранить</button>
        <a href="suppliers_read.php" class="btn btn-secondary">Назад</a>
    </form>
</body>
</html>
