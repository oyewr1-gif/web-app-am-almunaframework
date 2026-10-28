<?php
require_once APP_PATH . 'models/Produk.php';

class ProdukController {
    private $produkModel;

    public function __construct() {
        // Mengatur header agar response berupa JSON dan mengizinkan CORS
        header("Content-Type: application/json; charset=UTF-8");
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
        
        $this->produkModel = new Produk();
    }

    // GET: localhost/my-api/produk
    public function index() {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $data = $this->produkModel->getAll();
            echo json_encode(["status" => "success", "data" => $data]);
        } else {
            http_response_code(405);
            echo json_encode(["message" => "Method Not Allowed"]);
        }
    }

    // GET: localhost/my-api/produk/detail/1
    public function detail($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $data = $this->produkModel->getById($id);
            if ($data) {
                echo json_encode(["status" => "success", "data" => $data]);
            } else {
                http_response_code(404);
                echo json_encode(["message" => "Produk tidak ditemukan"]);
            }
        }
    }

    // POST: localhost/my-api/produk/tambah
    public function tambah() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Mengambil data raw JSON dari body request
            $input = json_decode(file_get_contents("php://input"), true);

            if (!empty($input['nama']) && !empty($input['harga'])) {
                $this->produkModel->create($input);
                http_response_code(201);
                echo json_encode(["message" => "Produk berhasil ditambahkan"]);
            } else {
                http_response_code(400);
                echo json_encode(["message" => "Data tidak lengkap"]);
            }
        }
    }
}
