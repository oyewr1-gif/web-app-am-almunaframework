<?php

class App 
{
    // Mengatur default controller, method, dan parameter jika URL kosong
    protected $controller = 'HomeController';
    protected $method = 'index';
    protected $params = [];

    public function __construct() 
    {
        $url = $this->parseUrl();

        // 1. VALIDASI & PENENTUAN CONTROLLER
        if (isset($url[0])) {
            // Keamanan: Hanya izinkan karakter alfanumerik untuk mencegah Path Traversal (e.g., ../../)
            $cleanController = preg_replace('/[^a-zA-Z0-9]/', '', $url[0]);
            $controllerName = ucfirst($cleanController) . 'Controller';

            // Periksa apakah file controller-nya benar-benar ada
            // APP_PATH didefinisikan di index.php (e.g., D:/Data/web/almunaframework/app/)
            if (file_exists(APP_PATH . 'controllers/' . $controllerName . '.php')) {
                $this->controller = $controllerName;
                unset($url[0]); // Hapus dari memori array URL
            } else {
                // Opsional: Arahkan ke halaman 404 jika controller tidak ditemukan
                $this->show404();
            }
        }

        // Memuat file controller yang terpilih
        require_once APP_PATH . 'controllers/' . $this->controller . '.php';
        
        // Inisialisasi class controller (e.g., $this->controller = new HomeController)
        $this->controller = new $this->controller;

        // 2. VALIDASI & PENENTUAN METHOD
        if (isset($url[1])) {
            $cleanMethod = preg_replace('/[^a-zA-Z0-9_]/', '', $url[1]);

            // Keamanan: Periksa apakah method ada di dalam class tersebut DAN bisa dipanggil secara publik
            if (method_exists($this->controller, $cleanMethod)) {
                $this->method = $cleanMethod;
                unset($url[1]);
            } else {
                $this->show404();
            }
        }

        // 3. PENGAMBILAN PARAMETER
        // Jika masih ada sisa array di URL, itu dianggap sebagai parameter (e.g., /id/5)
        $this->params = $url ? array_values($url) : [];

        // 4. EKSEKUSI JALUR (ZONA AMAN)
        // Memanggil method di dalam controller sambil mengirimkan parameter secara aman
        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    /**
     * Membaca, membersihkan, dan memecah URL
     */
    private function parseUrl() 
    {
        if (isset($_GET['url'])) {
            // Bersihkan tanda slash (/) di akhir URL
            $url = rtrim($_GET['url'], '/');
            // Filter URL dari karakter ilegal (mencegah XSS via URL)
            $url = filter_var($url, FILTER_SANITIZE_URL);
            // Pecah menjadi array berdasarkan tanda slash (/)
            return explode('/', $url);
        }
        return [];
    }

    /**
     * Menampilkan Halaman Error 404 Not Found secara aman
     */
    private function show404() 
    {
        http_response_code(404);
        if (file_exists(APP_PATH . 'views/errors/404.php')) {
            require_once APP_PATH . 'views/errors/404.php';
        } else {
            echo "<h1>404 Not Found</h1><p>Halaman yang Anda cari tidak ditemukan.</p>";
        }
        exit; // Hentikan eksekusi script sepenuhnya
    }
}
