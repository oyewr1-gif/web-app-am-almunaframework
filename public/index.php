<?php
	// Mendefinisikan root direktori aplikasi secara dinamis
	define('ROOT_PATH', dirname(__DIR__) . DIRECTORY_SEPARATOR); 
	// Gunakan dirname() untuk naik satu level jika index.php ada di dalam folder 'public'
	define('APP_PATH', ROOT_PATH . 'app' . DIRECTORY_SEPARATOR);
	define('VIEW_PATH', APP_PATH . 'views');

	// Mengimpor kelas parser
	require_once APP_PATH . '/core/DotEnv.php';
	// Menjalankan parser dengan mengarahkannya ke file .env di root
	try {
    	(new DotEnv(ROOT_PATH . '.env'))->load();
	} catch (Exception $e) {
    	echo "Gagal memuat konfigurasi lingkungan: " . $e->getMessage();
    	exit;
	}

	require_once APP_PATH . '/config/Config.php';
	
	// Jalankan aplikasi MVC Anda
	require_once APP_PATH . '/init.php';
	
	$app=new App;
