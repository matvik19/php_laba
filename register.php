<?php
// register.php
include 'db.php';

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Валидация данных
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Проверка обязательных полей
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $errors[] = "Все поля обязательны для заполнения.";
    }

    if (strlen($password) < 6) {
        $errors[] = "Пароль должен содержать не менее 6 символов.";
    }

    if ($password !== $confirm_password) {
        $errors[] = "Пароли не совпадают.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Некорректный email.";
    }

    // Проверка существования пользователя
    if (empty($errors)) {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = :username OR email = :email");
        $stmt->execute(['username' => $username, 'email' => $email]);
        $count = $stmt->fetchColumn();
        if ($count > 0) {
            $errors[] = "Пользователь с таким логином или email уже существует.";
        }
    }

    // Если ошибок нет, добавляем пользователя
    if (empty($errors)) {
        $password_hash = password_hash($password, PASSWORD_BCRYPT);
        // По умолчанию роль "Гость" (role_id = 1)
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password_hash, role_id) VALUES (:username, :email, :password_hash, 1)");
        $stmt->execute([
            'username' => $username,
            'email' => $email,
            'password_hash' => $password_hash
        ]);

        // Перенаправление на страницу входа
        header("Location: login.php");
        exit;
    }
}

include 'header.php';
?>

<h2>Регистрация</h2>

<?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
        <ul>
        <?php foreach ($errors as $error): ?>
            <li><?= htmlspecialchars($error) ?></li>
        <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<?php if ($success): ?>
    <div class="alert alert-success">
        <?= $success ?>
    </div>
<?php endif; ?>

<form action="register.php" method="post">
    <div class="form-group">
        <label for="username">Логин</label>
        <input type="text" name="username" id="username" class="form-control" 
               value="<?= isset($username) ? htmlspecialchars($username) : '' ?>" required>
    </div>
    <div class="form-group">
        <label for="email">Email</label>
        <input type="email" name="email" id="email" class="form-control" 
               value="<?= isset($email) ? htmlspecialchars($email) : '' ?>" required>
    </div>
    <div class="form-group">
        <label for="password">Пароль</label>
        <input type="password" name="password" id="password" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="confirm_password">Подтверждение пароля</label>
        <input type="password" name="confirm_password" id="confirm_password" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-primary">Зарегистрироваться</button>
</form>


