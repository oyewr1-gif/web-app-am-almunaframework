<?php

class Database 
{
    private $host;
    private $user;
    private $pass;
    private $name;

    private $dbh; // Database Handler
    private $stmt; // Statement

    public function __construct() 
    {
        // Mengambil kredensial secara aman dari $_ENV (hasil parser .env sebelumnya)
        $this->host = $_ENV['DB_HOST'] ?? 'localhost';
        $this->user = $_ENV['DB_USER'] ?? 'root';
        $this->pass = $_ENV['DB_PASS'] ?? '';
        $this->name = $_ENV['DB_NAME'] ?? '';

        // Data Source Name
        $dsn = "mysql:host=" . $this->host . ";dbname=" . $this->name . ";charset=utf8mb4";
        
        // Optimasi konfigurasi PDO
        $options = [
            PDO::ATTR_PERSISTENT => true, // Meningkatkan performa koneksi
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Lempar exception jika ada error SQL
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Output berupa Array Asosiatif
            PDO::ATTR_EMULATE_PREPARES => false, // Menggunakan prepared statement asli dari MySQL (Sangat Aman)
        ];

        try {
            $this->dbh = new PDO($dsn, $this->user, $this->pass, $options);
        } catch (PDOException $e) {
            // Jangan tampilkan pesan $e->getMessage() mentah-mentah di produksi karena membocorkan info server
            die("Koneksi database gagal!"); 
        }
    }

    // Menyiapkan query SQL
    public function query(string $sql) 
    {
        $this->stmt = $this->dbh->prepare($sql);
    }

    // Mengikat (bind) parameter secara otomatis untuk mencegah SQL Injection
    public function bind($param, $value, $type = null) 
    {
        if (is_null($type)) {
            switch (true) {
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
            }
        }
        $this->stmt->bindValue($param, $value, $type);
    }

    // Eksekusi query
    public function execute() 
    {
        return $this->stmt->execute();
    }

    // Mengambil banyak baris data (Select All)
    public function resultSet() 
    {
        $this->execute();
        return $this->stmt->fetchAll();
    }

    // Mengambil satu baris data saja (Select Single)
    public function single() 
    {
        $this->execute();
        return $this->stmt->fetch();
    }

    // Menghitung jumlah baris yang terdampak (untuk cek login, update, delete)
    public function rowCount() 
    {
        return $this->stmt->rowCount();
    }
}
