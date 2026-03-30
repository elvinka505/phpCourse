<?php
//восстанавливаем или создаем сессию
session_start();

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$errors = [];
//текущие введенные значения (чтобы пользователь не терял ввод)
$values = ['name' => '', 'email' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $values['name']  = trim($_POST['name'] ?? '');
    $values['email'] = trim($_POST['email'] ?? '');

    //проверяем токен
    if (($_POST['csrf_token'] ?? '') !== $_SESSION['csrf_token']) {
        $errors['csrf'] = 'Недействительный токен.';
    }

    //прохождение валидации
    if ($values['name'] === '') {
        $errors['name'] = 'Ваше имя слишком короткое';
    }
    if (!filter_var($values['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Некорректный email';
    }

    if (empty($errors)) {
        //кладем flash сообщение в сессию чтобы отправить после редиректа
        $_SESSION['flash'] = 'Форма отправлена!';
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        header('Location: ' . $_SERVER['REQUEST_URI']);
        exit;
    }
}

//достаем flash из сессии
$flash = $_SESSION['flash'] ?? null;
//сразу удаляем чтобы показалось только один раз
unset($_SESSION['flash']);
?>

<?php if ($flash): ?>
    <p style="color:hotpink"><?= htmlspecialchars($flash) ?></p>
<?php endif ?>

<form method="POST">
    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

    <input type="text" name="name" placeholder="Имя"
           value="<?= htmlspecialchars($values['name']) ?>">
    <?= $errors['name'] ?? '' ?>

    <input type="email" name="email" placeholder="Email"
           value="<?= htmlspecialchars($values['email']) ?>">
    <?= $errors['email'] ?? '' ?>

    <?= $errors['csrf'] ?? '' ?>

    <button type="submit">Отправить</button>
</form>
