<?php
// Jalankan fungsi autoloader otomatis
spl_autoload_register(function ($className) {
    // Tentukan direktori tempat mencari class
    $directories = [
        APP_PATH . 'core/',
        APP_PATH . 'controllers/',
        APP_PATH . 'models/'
    ];

    // Loop setiap direktori untuk mencari file class yang sesuai
    foreach ($directories as $directory) {
        $file = $directory . $className . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// Muat file .env secara otomatis
DotEnv::load(ROOT_PATH);
