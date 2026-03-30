<?php
require_once __DIR__ . '/Product.php';
require_once __DIR__ . '/Cart.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(404);
    header('Location: index.php');
    exit;
}

$products = [
    1 => new Product(1, 'Румяна', 5999),
    2 => new Product(2, 'Помада', 5699),
    3 => new Product(3, 'Пудра', 4999),
];

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!isset($_SESSION['cart']) || !($_SESSION['cart'] instanceof Cart)) {
    $_SESSION['cart'] = new Cart();
}

if (isset($products[$id])) {
    $_SESSION['cart']->add($products[$id]);
}

header('Location: index.php');
exit;