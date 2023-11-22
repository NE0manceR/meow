<?php
// Отримуємо URL
$url = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '/';

// Визначаємо, який файл обробляє URL

switch ($url) {
  case '/':
    require_once(__DIR__ . '/pages/home.php');
    break;

  case '/admin':
    require_once(__DIR__ . '/pages/admin.php');
    break;

  default:
    require_once(__DIR__ . '/pages/home.php');
    break;
}
