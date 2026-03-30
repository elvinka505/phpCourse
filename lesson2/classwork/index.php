<?php
function e($s) {
    return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}

$name = $_GET['name'] ?? '';
$role = $_GET['role'] ?? '';
//пока что не массив а строка с запятыми
$skillsStr = $_GET['skills'] ?? '';

//а для "навыков" нам пустой массив
$skills = [];
if ($skillsStr !== '') {
    foreach (explode(',', $skillsStr) as $skill) {
        //trim удаляет пробелы и переносы в начале строки
        $trimmed = trim($skill);
        if ($trimmed !== '') {
            $skills[] = $trimmed; //автодобавление в конец массива
        }
    }
}
//ассоциативный массив с тремя ключами
$profile = [
    'name' => $name,
    'role' => $role,
    'skills' => $skills, //ключ => знач, но тут само знач skills является массивом
    //!запятая
];

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Профиль</title>
</head>
<body>
<h1><?= e($profile['name']) ?></h1>
<p><?= e($profile['role']) ?></p>

<h2>Навыки</h2>
<ol>
    <?php foreach ($profile['skills'] as $skill): ?>
        <li><?= e($skill) ?></li>
    <?php endforeach; ?>
</ol>
</body>
</html>

