<?php
// header.php
session_start();

// Подключение к базе данных, если пользователь не в сессии, но есть куки
if (!isset($_SESSION['user']) && isset($_COOKIE['remember_me'])) {
    include 'db.php';
    $token = $_COOKIE['remember_me'];
    $stmt = $pdo->prepare("SELECT users.*, roles.role_name FROM users 
                           JOIN roles ON users.role_id = roles.role_id 
                           WHERE remember_token = :token");
    $stmt->execute(['token' => $token]);
    $user = $stmt->fetch();
    if ($user) {
        $_SESSION['user'] = [
            'user_id' => $user['user_id'],
            'username' => $user['username'],
            'role_name' => $user['role_name'],
            'last_login' => $user['last_login'],
            'visit_count' => $user['visit_count'],
            'header_color' => $user['header_color'] // Добавляем цвет заголовка в сессию
        ];
    }
}

// Если пользователь уже в сессии, убедимся, что `header_color` присутствует
if (isset($_SESSION['user'])) {
    if (!isset($_SESSION['user']['header_color'])) {
        include 'db.php';
        $stmt = $pdo->prepare("SELECT header_color FROM users WHERE user_id = :user_id");
        $stmt->execute(['user_id' => $_SESSION['user']['user_id']]);
        $result = $stmt->fetch();
        if ($result) {
            $_SESSION['user']['header_color'] = $result['header_color'];
        } else {
            $_SESSION['user']['header_color'] = 'light'; // Значение по умолчанию
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Коммерческие Фирмы - <?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) : 'Главная'; ?></title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="css/styles.css" rel="stylesheet">
</head>
<body>
<?php
// Определение класса цвета для navbar
$headerColorClass = 'bg-light'; // Значение по умолчанию
if (isset($_SESSION['user']['header_color'])) {
    switch ($_SESSION['user']['header_color']) {
        case 'blue':
            $headerColorClass = 'bg-primary';
            break;
        case 'green':
            $headerColorClass = 'bg-success';
            break;
        case 'light':
        default:
            $headerColorClass = 'bg-light';
            break;
    }
}
?>
<nav class="navbar navbar-expand-lg <?php echo $headerColorClass; ?> navbar-light">
  <a class="navbar-brand" href="index.php">Коммерческие Фирмы</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" 
          aria-controls="navbarNav" aria-expanded="false" aria-label="Переключить навигацию">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ml-auto">
          <?php if (isset($_SESSION['user'])): ?>
              <?php if ($_SESSION['user']['role_name'] === 'Админ'): ?>
                  <li class="nav-item">
                      <a class="nav-link" href="admin.php">Админка</a>
                  </li>
              <?php endif; ?>
              <?php if ($_SESSION['user']['role_name'] === 'Оператор' || $_SESSION['user']['role_name'] === 'Админ'): ?>
                  <li class="nav-item">
                      <a class="nav-link" href="reports.php">Отчеты</a>
                  </li>
              <?php endif; ?>
              <li class="nav-item">
                  <a class="nav-link" href="logout.php">Выйти</a>
              </li>
          <?php else: ?>
              <li class="nav-item">
                  <a class="nav-link" href="login.php">Войти</a>
              </li>
              <li class="nav-item">
                  <a class="nav-link" href="register.php">Регистрация</a>
              </li>
          <?php endif; ?>
      </ul>
  </div>
</nav>
<div class="container mt-4">
