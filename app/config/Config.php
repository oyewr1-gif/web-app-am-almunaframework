<?php
  // Mengaktifkan mode debug
  ini_set('display_errors', 1);
  ini_set('error_reporting', E_ALL);

  // Membuat konstanta global dari file .env
	define('BASE_URL', $_ENV['BASE_URL']);
  define('API_URL', $_ENV['API_URL']);
