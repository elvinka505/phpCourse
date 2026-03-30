<?php
function e($s) {
    return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}

//получаем параметры из GET, дефолты через ??
$q = $_GET['q'] ?? '';
$minPrice = (int)($_GET['min'] ?? 0);
$maxPrice = (int)($_GET['max'] ?? PHP_INT_MAX);
$sort = $_GET['sort'] ?? 'name';
$dir = $_GET['dir'] ?? 'asc';
$page = (int)($_GET['page'] ?? 1);
$perPage = (int)($_GET['perPage'] ?? 5);

//массив товаров косметики
$products = [
    ['id' => 1, 'name' => 'Помада MAC Ruby Woo', 'price' => 2500, 'tags' => ['помада', 'mac']],
    ['id' => 2, 'name' => 'Тональный крем Estée Lauder', 'price' => 4500, 'tags' => ['тон', 'estee-lauder']],
    ['id' => 3, 'name' => 'Тушь L\'Oréal Paris', 'price' => 1200, 'tags' => ['тушь', 'loreal']],
    ['id' => 4, 'name' => 'Пудра Rimmel Stay Matte', 'price' => 800, 'tags' => ['пудра', 'rimmel']],
    ['id' => 5, 'name' => 'Сыворотка La Roche-Posay', 'price' => 3500, 'tags' => ['сыворотка', 'ла-рош']],
    ['id' => 6, 'name' => 'Крем для лица CeraVe', 'price' => 1500, 'tags' => ['крем', 'cerave']],
    ['id' => 7, 'name' => 'Маска для волос L\'Occitane', 'price' => 2800, 'tags' => ['маска', 'loccitane']],
    ['id' => 8, 'name' => 'Парфюм Chanel No.5', 'price' => 12000, 'tags' => ['парфюм', 'chanel']],
    ['id' => 9, 'name' => 'Блеск для губ NYX', 'price' => 700, 'tags' => ['блеск', 'nyx']],
    ['id' => 10, 'name' => 'Основа под макияж Maybelline', 'price' => 900, 'tags' => ['основа', 'maybelline']],
];

$filtered = [];
foreach ($products as $product) {
    //strtolower() + strpos() = чтобы без регулярок (проблема:чувствительность к регистру)
    //implode = преобр теги в строку
    //continue = пропуск неподходящих
    $searchText = strtolower($product['name'] . ' ' . implode(' ', $product['tags']));
    if ($q !== '' && strpos($searchText, strtolower($q)) === false) continue;
    if ($product['price'] < $minPrice || $product['price'] > $maxPrice) continue;
    $filtered[] = $product;
}

usort($filtered, function($a, $b) use ($sort, $dir) {
    //spaceship (-1 0 1)
    $result = $sort === 'price' ? $a['price'] <=> $b['price'] : strtolower($a['name']) <=> strtolower($b['name']);
    //инверсия
    return $dir === 'asc' ? $result : -$result;
});

//пагинация
$total = count($filtered);
$totalPages = ceil($total / $perPage);
$offset = ($page - 1) * $perPage; //смещение для текущей страницы
$paginated = array_slice($filtered, $offset, $perPage); //берет нужный кусок
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Косметика</title>
</head>
<body>
<h1>Товары (<?= count($paginated) ?>/<?= $total ?>)</h1>

<?php if (empty($paginated)): ?>
    <p>Ничего не найдено</p>
<?php else: ?>
    <ul>
        <?php foreach ($paginated as $product): ?>
            <li>
                <?= e($product['name']) ?> -
                <?= number_format($product['price']) ?>₽
                (<?= implode(', ', array_map('e', $product['tags'])) ?>)
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<?php if ($totalPages > 1): ?>
    <p>Страница <?= $page ?> из <?= $totalPages ?></p>
    <?php if ($page > 1): ?>
        <a href="?page=<?= $page-1 ?>&q=<?= e($q) ?>&min=<?= $minPrice ?>&max=<?= $maxPrice ?>&sort=<?= e($sort) ?>&dir=<?= e($dir) ?>&perPage=<?= $perPage ?>">Пред</a>
    <?php endif; ?>
    <?php if ($page < $totalPages): ?>
        <a href="?page=<?= $page+1 ?>&q=<?= e($q) ?>&min=<?= $minPrice ?>&max=<?= $maxPrice ?>&sort=<?= e($sort) ?>&dir=<?= e($dir) ?>&perPage=<?= $perPage ?>">След</a>
    <?php endif; ?>
<?php endif; ?>
</body>
</html>
