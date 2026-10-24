<?php

class Controller 
{

    public function __construct()
    {
        // Otomatis periksa token setiap kali ada pengiriman data via POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!csrf_validate()) {
                $this->showCsrfError();
            }
        }
    }
    
    /**
     * Menampilkan error khusus jika validasi CSRF gagal
     */
    private function showCsrfError()
    {
        http_response_code(403); // 403 Forbidden
        if (file_exists(APP_PATH . 'views/errors/403.php')) {
            require_once APP_PATH . 'views/errors/403.php';
        } else {
            echo "<h1>403 Forbidden</h1><p>Permintaan ditolak. Sesi CSRF tidak valid atau telah kedaluwarsa.</p>";
        }
        exit;
    }

    /**
     * Memanggil dan menginisialisasi berkas Model secara aman
     * 
     * @param string $model Nama class model yang ingin dipanggil
     * @return object Instansiasi dari class model
     */
    public function model(string $model)
    {
        // 1. Validasi Keamanan: Cegah Path Traversal (hanya izinkan huruf dan angka)
        $cleanModel = preg_replace('/[^a-zA-Z0-9]/', '', $model);

        $modelPath = APP_PATH . 'models/' . $cleanModel . '.php';

        // 2. Periksa keberadaan file
        if (!file_exists($modelPath)) {
            throw new \Exception("Model File '{$cleanModel}.php' tidak ditemukan di direktori models.");
        }

        require_once $modelPath;

        // 3. Periksa apakah class tersebut benar-after di-load
        if (!class_exists($cleanModel)) {
            throw new \Exception("Class Model '{$cleanModel}' tidak ditemukan di dalam file.");
        }

        return new $cleanModel();
    }

    /**
     * Memuat halaman Tampilan (View) secara aman sambil mengirimkan data
     * 
     * @param string $view Jalur file view (bisa menggunakan dot notation atau slash, misal: 'home/index')
     * @param array $data Data yang akan diekstrak ke dalam view
     */
    public function view(string $view, array $data = [])
    {
        // 1. Validasi Keamanan: Izinkan huruf, angka, slash (/), dan dot (.)
        $cleanView = preg_replace('/[^a-zA-Z0-9\/\.]/', '', $view);
        
        // Mengubah format dot notation (jika ada, misal: 'home.index' menjadi 'home/index')
        $cleanView = str_replace('.', '/', $cleanView);

        $viewPath = APP_PATH . 'views/' . $cleanView . '.php';

        // 2. Periksa keberadaan file view
        if (!file_exists($viewPath)) {
            $this->showError("View File '{$cleanView}.php' tidak ditemukan.");
        }

        // 3. ZONA AMAN: Ekstrak data array menjadi variabel lokal
        // Mencegah Overwriting variabel krusial dengan EXTR_SKIP
        extract($data, EXTR_SKIP);

        // 4. Muat tampilan halaman
        require_once $viewPath;
    }

    /**
     * Fungsi pembantu untuk menampilkan error internal (bukan 404)
     */
    private function showError(string $message)
    {
        http_response_code(500);
        if (file_exists(APP_PATH . 'views/errors/500.php')) {
            $data['message'] = $message;
            require_once APP_PATH . 'views/errors/500.php';
        } else {
            echo "<h1>500 Internal Server Error</h1><p>" . htmlspecialchars($message) . "</p>";
        }
        exit;
    }
}
