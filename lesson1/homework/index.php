<?php
$name = htmlspecialchars($_GET['name'] ?? 'Гость', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
$role = $_GET['role'] ?? '';

$greeting = "Добрый день";
if ($role === 'admin') {
    $greeting .= ", админ";
}
$greeting .= " $name";

echo $greeting . "<br>\n";
echo "Метод: " . $_SERVER['REQUEST_METHOD'] . "<br>\n";
echo "URI: " . htmlspecialchars($_SERVER['REQUEST_URI'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');