<?php
// index.php
include 'db.php';
include 'header.php';

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

