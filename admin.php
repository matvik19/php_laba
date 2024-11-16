<?php
// admin.php
include 'db.php';
include 'header.php';

// Проверка, что пользователь авторизован и является администратором
if (!isset($_SESSION['user']) || $_SESSION['user']['role_name'] !== 'Админ') {
    echo "<div class='alert alert-danger'>Доступ запрещен. Требуются права администратора.</div>";
    include 'footer.php';
    exit;
}

// Получение списка пользователей с ролью "Гость"
$stmt = $pdo->prepare("SELECT users.*, roles.role_name FROM users 
                       JOIN roles ON users.role_id = roles.role_id 
                       WHERE roles.role_name = 'Гость'");
$stmt->execute();
$guests = $stmt->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['approve_user_id'])) {
    $approve_user_id = intval($_POST['approve_user_id']);
    // Назначение роли "Оператор"
    $stmt = $pdo->prepare("UPDATE users SET role_id = (SELECT role_id FROM roles WHERE role_name = 'Оператор') WHERE user_id = :user_id");
    $stmt->execute(['user_id' => $approve_user_id]);
    // Перезагрузка страницы
    header("Location: admin.php");
    exit;
}
?>

<h2>Модерация пользователей</h2>

<?php if (empty($guests)): ?>
    <div class="alert alert-info">Нет пользователей, ожидающих модерации.</div>
<?php else: ?>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Имя пользователя</th>
                <th>Email</th>
                <th>Дата регистрации</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($guests as $guest): ?>
                <tr>
                    <td><?= htmlspecialchars($guest['username']) ?></td>
                    <td><?= htmlspecialchars($guest['email']) ?></td>
                    <td><?= htmlspecialchars($guest['created_at']) ?></td>
                    <td>
                        <form action="admin.php" method="post" style="display:inline;">
                            <input type="hidden" name="approve_user_id" value="<?= $guest['user_id'] ?>">
                            <button type="submit" class="btn btn-sm btn-success" 
                                    onclick="return confirm('Назначить роль Оператор?')">Одобрить</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<?php include 'main_crud.php'; ?>

<?php
include 'footer.php';
?>
