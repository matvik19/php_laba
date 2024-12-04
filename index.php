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
        <h2>Выберите цвет заголовка</h2>
        <form action='index.php' method='post'>
            <div class='form-group'>
                <label for='header_color'>Цвет:</label>
                <select class='form-control' id='header_color' name='header_color' required>
                    <option value='light' " . ($currentColor === 'light' ? 'selected' : '') . ">Светлый</option>
                    <option value='blue' " . ($currentColor === 'blue' ? 'selected' : '') . ">Синий</option>
                    <option value='green' " . ($currentColor === 'green' ? 'selected' : '') . ">Зелёный</option>
                </select>
            </div>
            <button type='submit' class='btn btn-primary'>Сохранить</button>
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

<div class="jumbotron">
    <?= $pageContent ?>
</div>

<!-- Закрывающие теги HTML -->
</div> <!-- Закрытие контейнера -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
