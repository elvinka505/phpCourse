<?php

$getRoutes = [
        '' => function() { ?>
            <!DOCTYPE html>
            <html lang="ru">
            <head><meta charset="UTF-8"><title>Главная</title></head>
            <body>
            <h1>Главная</h1>
            </body>
            </html>
        <?php },

        'about' => function() { ?>
            <!DOCTYPE html>
            <html lang="ru">
            <head><meta charset="UTF-8"><title>О нас</title></head>
            <body>
            <h1>О нас</h1>
            </body>
            </html>
        <?php },

        'contact' => function() { ?>
            <!DOCTYPE html>
            <html lang="ru">
            <head><meta charset="UTF-8"><title>Контакт</title></head>
            <body>
            <h1>Контакт</h1>
            <form method="POST" action="/contact">
                <label for="name">Имя:</label><br>
                <input type="text" id="name" name="name"><br><br>

                <label for="message">Сообщение:</label><br>
                <textarea id="message" name="message"></textarea><br><br>

                <button type="submit">Отправить</button>
            </form>
            </body>
            </html>
        <?php },
];

$postRoutes = [
        'contact' => function(array $data) {
            $name    = htmlspecialchars($data['name'] ?? '', ENT_QUOTES, 'UTF-8');
            $message = htmlspecialchars($data['message'] ?? '', ENT_QUOTES, 'UTF-8');
            ?>
            <!DOCTYPE html>
            <html lang="ru">
            <head><meta charset="UTF-8"><title>Спасибо</title></head>
            <body>
            <h1>Спасибо, <?= $name ?>!</h1>
            <p><strong>Ваше сообщение:</strong> <?= $message ?></p>
            <a href="/contact">← Отправить ещё</a>
            </body>
            </html>
        <?php },
];

$method = $_SERVER['REQUEST_METHOD'];
$path   = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');

switch ($method) {
    case 'GET':
        if (array_key_exists($path, $getRoutes)) {
            $getRoutes[$path]();
        } else {
            http_response_code(404);
            echo '<h1>404 — страница не найдена</h1>';
        }
        break;

    case 'POST':
        if (array_key_exists($path, $postRoutes)) {
            $postRoutes[$path]($_POST);
        } else {
            http_response_code(404);
            echo '<h1>404 — маршрут не найден</h1>';
        }
        break;

    default:
        http_response_code(405);
        echo '<h1>405 — метод не поддерживается</h1>';
}
