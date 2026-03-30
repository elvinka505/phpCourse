<?php
$routes = [
    "" => function() { echo "<h1>Главная</h1>"; },
    "about" => function() { echo "<h1>О нас</h1>"; },
    "users" => function() { echo "<h1>Пользователи</h1>"; }
];

$path = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), "/");
//проверяем, есть ли такой маршрут
if (array_key_exists($path, $routes)) {
    $routes[$path](); //вызываем анонимную функцию
} else {
    http_response_code(404);
    echo "<h1>404</h1>";
}
?>
