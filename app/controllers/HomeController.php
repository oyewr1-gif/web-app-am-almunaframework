<?php
class HomeController extends Controller {

    public function __construct() {
        parent::__construct(); // WAJIB dipanggil agar sistem CSRF aktif di class ini
        // Kode inisialisasi internal milik AuthController Anda...
    }

    public function index() {
        // Memanggil Model secara aman
        $userModel = $this->model('UserModel');
        $allUsers  = $userModel->getAll();

        // Menyiapkan data untuk dikirim ke view
        $data = [
            'title' => 'Halaman Utama',
            'users' => $allUsers
        ];

        // Memuat View secara aman
        $this->view('home/index', $data);
    }
}