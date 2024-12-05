<?php
// index.php
include 'db.php';
include 'header.php';

// Обработка отправки формы выбора цвета
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['header_color'])) {
    if (isset($_SESSION['user'])) {
        $color = $_POST['header_color'];
        $allowed_colors = ['blue', 'light', 'green'];

        if (in_array($color, $allowed_colors)) {
            // Обновление цвета в базе данных
            $stmt = $pdo->prepare("UPDATE users SET header_color = :color WHERE user_id = :user_id");
            $stmt->execute([
                'color' => $color,
                'user_id' => $_SESSION['user']['user_id']
            ]);

            // Обновление цвета в сессии
            $_SESSION['user']['header_color'] = $color;
        }
    }
}

// Обновление информации о пользователе (например, посещений и времени входа)
if (isset($_SESSION['user'])) {
    // Обновление времени последнего входа и счетчика посещений
    $stmt = $pdo->prepare("UPDATE users SET last_login = NOW(), visit_count = visit_count + 1 WHERE user_id = :user_id");
    $stmt->execute(['user_id' => $_SESSION['user']['user_id']]);

    // Обновление данных в сессии
    $_SESSION['user']['last_login'] = date("Y-m-d H:i:s"); // Можно получить из базы данных, если нужно точнее
    $_SESSION['user']['visit_count'] += 1;
}

// Обработка формы для запроса характеристик автомобилей
$cars = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['min_price']) && isset($_POST['max_price'])) {
    $min_price = $_POST['min_price'];
    $max_price = $_POST['max_price'];

    // Выполнение запроса к базе данных
    $stmt = $pdo->prepare("SELECT * FROM get_car_technical_specs(:min_price, :max_price)");
    $stmt->bindParam(':min_price', $min_price, PDO::PARAM_STR);
    $stmt->bindParam(':max_price', $max_price, PDO::PARAM_STR);
    $stmt->execute();
    $cars = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

if (isset($_SESSION['user'])) {
    $user = $_SESSION['user'];
    if ($user['role_name'] === 'Админ') {
        $pageContent = "Добро пожаловать, Админ! Вы можете управлять системой.";
    } elseif ($user['role_name'] === 'Оператор') {
        if ($user['visit_count'] === 1) {
            $pageContent = "Добро пожаловать!";
        } else {
            $lastLogin = htmlspecialchars($user['last_login']);
            $visitCount = htmlspecialchars($user['visit_count']);
            $pageContent = "Вы имеете статус оператора, можете формировать отчеты!<br>Вы зашли в систему $visitCount раз.<br>Последнее посещение: $lastLogin";
        }
    } else {
        // Для других авторизованных пользователей
        $pageContent = "Добро пожаловать, " . htmlspecialchars($user['username']) . "! Заявка на модерацию была отправлена. Пожалуйста, ожидайте пока администратор ее обработает";
    }

    // Форма выбора цвета заголовка
    $currentColor = isset($user['header_color']) ? $user['header_color'] : 'light';
    $pageContent .= "
        <hr>
        <div class='header-color-container'>
            <form action='index.php' method='post' class='header-color-form'>
                <div class='form-group'>
                    <label for='header_color'>Выберите цвет темы:</label>
                    <select class='form-control' id='header_color' name='header_color' required>
                        <option value='light' " . ($currentColor === 'light' ? 'selected' : '') . ">Светлый</option>
                        <option value='blue' " . ($currentColor === 'blue' ? 'selected' : '') . ">Синий</option>
                        <option value='green' " . ($currentColor === 'green' ? 'selected' : '') . ">Зелёный</option>
                    </select>
                </div>
                <button type='submit' class='btn btn-primary'>Сохранить</button>
            </form>
        </div>
    ";

    // Форма для выбора диапазона цен
    $pageContent .= "
        <hr>
        <h2>Выберите диапазон цен</h2>
        <form method='POST'>
            <div class='form-group'>
                <label for='min_price'>Минимальная цена:</label>
                <input type='number' name='min_price' id='min_price' class='form-control' required>
            </div>
            <div class='form-group'>
                <label for='max_price'>Максимальная цена:</label>
                <input type='number' name='max_price' id='max_price' class='form-control' required>
            </div>
            <button type='submit' class='btn btn-primary'>Поиск</button>
        </form>
    ";
} else {
    // Для гостей
    $pageContent = "
        <h1>Добро пожаловать на наш сайт!</h1>
        <p>Коммерческие фирмы — это веб-приложение, предназначенное для управления данными о продажах автомобилей, формирования отчетов и администрирования пользователей.</p>
        <p>Разработчик проекта: Щербаев Матвей Олегович</p>
        <p>Стек: PHP, HTML, CSS</p>
        <h2>Регистрация</h2>
        <form action='register.php' method='get'>
            <button type='submit' class='btn btn-primary'>Зарегистрироваться</button>
        </form>
    ";
}

?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Коммерческие фирмы</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Позиционируем форму для выбора цвета в правом верхнем углу */
        .header-color-container {
            position: fixed;
            top: 110px;
            right: 20px;
            width: 250px;
            padding: 15px;
            border: 1px solid #ccc;
            background-color: #f9f9f9;
            box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
        }

        .header-color-form {
            margin: 0;
        }

        .header-color-form button {
            font-size: 14px;
            padding: 6px 12px;
        }

    </style>
</head>
<body>
    <div class="jumbotron">
        <?= $pageContent ?>

        <!-- Отображение результатов поиска автомобилей -->
        <?php if (!empty($cars)): ?>
            <h2>Технические характеристики автомобилей</h2>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Модель</th>
                        <th>Цвет</th>
                        <th>Обивка</th>
                        <th>Мощность двигателя</th>
                        <th>Количество дверей</th>
                        <th>Тип трансмиссии</th>
                        <th>Цена</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cars as $car): ?>
                        <tr>
                            <td><?= htmlspecialchars($car['model_name']) ?></td>
                            <td><?= htmlspecialchars($car['model_color']) ?></td>
                            <td><?= htmlspecialchars($car['model_upholstery']) ?></td>
                            <td><?= htmlspecialchars($car['engine_power']) ?></td>
                            <td><?= htmlspecialchars($car['door_count']) ?></td>
                            <td><?= htmlspecialchars($car['transmission_type']) ?></td>
                            <td><?= htmlspecialchars($car['car_price']) ?> ₽</td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="alert alert-info mt-3">В указанном диапазоне данных нет.</div>    
        <?php endif; ?>
    </div>

    <!-- Закрывающие теги HTML -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
