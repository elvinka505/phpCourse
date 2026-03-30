<?php

require_once 'Product.php';

$data = [
    ['id' => 1, 'title' => 'Помада', 'price' => 3499.99],
    ['id' => 2, 'title' => 'Пудра', 'price' => 4499.99],
    ['id' => 3, 'title' => 'Румяна', 'price' => 2999.99]
];

$products = [];


foreach ($data as $item) {
    $products[] = new Product(
        $item['id'],
        $item['title'],
        $item['price']
    );
}

?>

<!DOCTYPE html>
<html>
<body>

<h1>Список товаров</h1>

<ul>
    <?php foreach ($products as $product): ?>
        <li>
            <?= htmlspecialchars($product->getTitle()) ?> —
            <?= $product->getFormattedPrice() ?>
        </li>
    <?php endforeach; ?>
</ul>

</body>
</html>