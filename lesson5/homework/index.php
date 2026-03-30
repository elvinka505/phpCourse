<?php
require_once __DIR__ . '/Product.php';
require_once __DIR__ . '/Cart.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$products = [
        new Product(1, 'Румяна', 5999),
        new Product(2, 'Помада', 5699),
        new Product(3, 'Пудра', 4999),
];

if (!isset($_SESSION['cart']) || !($_SESSION['cart'] instanceof Cart)) {
    $_SESSION['cart'] = new Cart();
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Товары</title>
</head>
<body>
<h1>Список товаров</h1>

<ul>
    <?php foreach ($products as $product): ?>
        <li>
            <?= htmlspecialchars($product->getTitle()) ?> —
            <?= $product->getFormattedPrice() ?>
            <a href="add.php?id=<?= $product->getId() ?>">Добавить в корзину</a>
        </li>
    <?php endforeach; ?>
</ul>

<p><a href="cart_view.php">Перейти в корзину</a></p>
</body>
</html>