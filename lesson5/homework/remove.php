<?php
require_once __DIR__ . '/Cart.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(404);
    header('Location: index.php');
    exit;
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (isset($_SESSION['cart']) && $_SESSION['cart'] instanceof Cart) {
    $_SESSION['cart']->remove($id);
}

header('Location: cart_view.php');
exit;