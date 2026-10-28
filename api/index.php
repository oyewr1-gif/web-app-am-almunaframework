<?php
// Definisikan ROOT_PATH untuk mendeteksi folder utama tempat file .env berada
define('ROOT_PATH', dirname(__DIR__) . DIRECTORY_SEPARATOR . 'api' . DIRECTORY_SEPARATOR);
// Definisikan APP_PATH ke folder app induk secara absolut
define('APP_PATH', ROOT_PATH . 'app' . DIRECTORY_SEPARATOR);

// Panggil konfigurasi autoloader
require_once APP_PATH . 'init.php';

// Jalankan aplikasi
$app = new App();

