<?php
session_start();

if (!isset($_SESSION['tasks'])) {
    $_SESSION['tasks'] = [];
}

$action = $_GET['action'] ?? 'list';
$allowedActions = ['list', 'create', 'edit'];

if (!in_array($action, $allowedActions)) {
    http_response_code(404);
    echo '<h1>404 Not Found</h1>';
    exit;
}

if ($action === 'create' && $_SERVER['REQUEST_METHOD'] === 'POST') {

    if ($_POST['csrf'] !== ($_SESSION['csrf'] ?? '')) {
        die('Ошибка CSRF');
    }

    $title = trim($_POST['title']);
    $desc = trim($_POST['description']);

    if (strlen($title) < 3) {
        $_SESSION['error'] = 'Короткий заголовок';
        header('Location: ?action=create');
        exit;
    }

    $filename = null;

    if (!empty($_FILES['file']['name'])) {
        $file = $_FILES['file'];

        if ($file['error'] === 0) {
            $filename = time() . '_' . $file['name'];
            move_uploaded_file($file['tmp_name'], 'uploads/' . $filename);
        }
    }

    $_SESSION['tasks'][] = [
            'id' => uniqid(),
            'title' => $title,
            'description' => $desc,
            'file' => $filename
    ];

    $_SESSION['success'] = 'Создано!';
    header('Location: index.php');
    exit;
}

if ($action === 'edit' && $_SERVER['REQUEST_METHOD'] === 'POST') {

    if ($_POST['csrf'] !== ($_SESSION['csrf'] ?? '')) {
        die('Ошибка CSRF');
    }

    $id = $_POST['id'];
    $title = trim($_POST['title']);
    $desc = trim($_POST['description']);

    if (strlen($title) < 3) {
        $_SESSION['error'] = 'Короткий заголовок';
        header("Location: ?action=edit&id=$id");
        exit;
    }

    foreach ($_SESSION['tasks'] as &$task) {
        if ($task['id'] === $id) {

            $task['title'] = $title;
            $task['description'] = $desc;

            if (!empty($_FILES['file']['name'])) {
                $file = $_FILES['file'];

                if ($file['error'] === 0) {
                    $filename = time() . '_' . $file['name'];
                    move_uploaded_file($file['tmp_name'], 'uploads/' . $filename);

                    $task['file'] = $filename;
                }
            }

            break;
        }
    }

    $_SESSION['success'] = 'Обновлено!';
    header('Location: index.php');
    exit;
}
?>

<!DOCTYPE html>
<html>
<body>

<h1>Список задач</h1>

<?php if (!empty($_SESSION['error'])): ?>
    <p style="color:red"><?= $_SESSION['error'] ?></p>
    <?php unset($_SESSION['error']); endif; ?>

<?php if (!empty($_SESSION['success'])): ?>
    <p style="color:green"><?= $_SESSION['success'] ?></p>
    <?php unset($_SESSION['success']); endif; ?>

<a href="?action=create">Создать задачу</a>

<hr>

<?php if ($action === 'create' || $action === 'edit'): ?>

    <?php
    $token = bin2hex(random_bytes(16));
    $_SESSION['csrf'] = $token;

    $editTask = null;

    if ($action === 'edit' && isset($_GET['id'])) {
        foreach ($_SESSION['tasks'] as $t) {
            if ($t['id'] === $_GET['id']) {
                $editTask = $t;
                break;
            }
        }
    }
    ?>

    <form method="POST" enctype="multipart/form-data">

        <?php if ($editTask): ?>
            <input type="hidden" name="id" value="<?= $editTask['id'] ?>">
        <?php endif; ?>

        <input name="title" placeholder="Заголовок"
               value="<?= $editTask ? htmlspecialchars($editTask['title']) : '' ?>">
        <br>

        <textarea name="description"><?= $editTask ? htmlspecialchars($editTask['description']) : '' ?></textarea>
        <br>

        <input type="file" name="file">
        <br>

        <input type="hidden" name="csrf" value="<?= $token ?>">

        <button>
            <?= $editTask ? 'Обновить' : 'Сохранить' ?>
        </button>

    </form>

<?php else: ?>

    <?php foreach ($_SESSION['tasks'] as $task): ?>
        <div>
            <h3><?= htmlspecialchars($task['title']) ?></h3>
            <p><?= htmlspecialchars($task['description']) ?></p>

            <?php if ($task['file']): ?>
                <img src="uploads/<?= htmlspecialchars($task['file']) ?>" width="100">
            <?php endif; ?>

            <br>
            <a href="?action=edit&id=<?= $task['id'] ?>">Редактировать</a>
        </div>
        <hr>
    <?php endforeach; ?>

<?php endif; ?>

</body>
</html>