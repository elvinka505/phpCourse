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

$cart = $_SESSION['cart'] ?? new Cart();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Корзина</title>
</head>
<body>
<h1>Корзина</h1>

<?php if (empty($cart->getItems())): ?>
    <p>Корзина пуста.</p>
<?php else: ?>
    <table border="1" cellpadding="8" cellspacing="0">
        <tr>
            <th>Товар</th>
            <th>Количество</th>
            <th>Цена за единицу</th>
            <th>Сумма</th>
            <th>Действие</th>
        </tr>

        <?php foreach ($cart->getItems() as $item): ?>
            <?php
            $product = $item['product'];
            $quantity = $item['quantity'];
            $sum = $product->getPrice() * $quantity;
            ?>
            <tr>
                <td><?= htmlspecialchars($product->getTitle()) ?></td>
                <td><?= $quantity ?></td>
                <td><?= $product->getFormattedPrice() ?></td>
                <td><?= number_format($sum, 2, '.', ' ') ?> руб.</td>
                <td>
                    <a href="remove.php?id=<?= $product->getId() ?>">Удалить</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    <p><strong>Итого:</strong> <?= number_format($cart->getTotal(), 2, '.', ' ') ?> руб.</p>
    <p><a href="clear.php">Очистить корзину</a></p>
<?php endif; ?>

<p><a href="index.php">Назад к товарам</a></p>
</body>
</html>