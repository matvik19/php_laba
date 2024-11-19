<?php
// login.php
include 'db.php';
include 'header.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username_or_email = trim($_POST['username_or_email']);
    $password = $_POST['password'];
    $remember = isset($_POST['remember']);

    if (empty($username_or_email) || empty($password)) {
        $errors[] = "Все поля обязательны для заполнения.";
    } else {
        // Поиск пользователя
        $stmt = $pdo->prepare("SELECT users.*, roles.role_name FROM users 
                               JOIN roles ON users.role_id = roles.role_id 
                               WHERE username = :username OR email = :email");
        $stmt->execute(['username' => $username_or_email, 'email' => $username_or_email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password_hash'])) {
            // Обновление данных о последнем входе и увеличении счетчика посещений
            $stmt = $pdo->prepare("UPDATE users SET last_login = NOW(), visit_count = visit_count + 1 WHERE user_id = :user_id");
            $stmt->execute(['user_id' => $user['user_id']]);

            // Сохранение данных пользователя в сессии
            $_SESSION['user'] = [
                'user_id' => $user['user_id'],
                'username' => $user['username'],
                'role_name' => $user['role_name'],
                'last_login' => $user['last_login'],
                'visit_count' => $user['visit_count'] + 1
            ];

            // Установка куки "Запомнить меня"
            if ($remember) {
                $token = bin2hex(random_bytes(16));
                setcookie('remember_me', $token, time() + (86400 * 30), "/"); // 30 дней

                // Сохранение токена в базе данных
                $stmt = $pdo->prepare("UPDATE users SET remember_token = :token WHERE user_id = :user_id");
                $stmt->execute(['token' => $token, 'user_id' => $user['user_id']]);
            }

            // Перенаправление на главную страницу
            header("Location: index.php");
            exit;
        } else {
            $errors[] = "Неверный логин или пароль.";
        }
    }
}
?>

<h2>Вход</h2>

<?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
        <ul>
        <?php foreach ($errors as $error): ?>
            <li><?= htmlspecialchars($error) ?></li>
        <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form action="login.php" method="post">
    <div class="form-group">
        <label for="username_or_email">Логин или Email</label>
        <input type="text" name="username_or_email" id="username_or_email" class="form-control" 
               value="<?= isset($username_or_email) ? htmlspecialchars($username_or_email) : '' ?>" required>
    </div>
    <div class="form-group">
        <label for="password">Пароль</label>
        <input type="password" name="password" id="password" class="form-control" required>
    </div>
    <div class="form-group form-check">
        <input type="checkbox" name="remember" id="remember" class="form-check-input">
        <label for="remember" class="form-check-label">Запомнить меня</label>
    </div>
    <button type="submit" class="btn btn-primary">Войти</button>
</form>


