<?php
class Database {
    private $host = "localhost";
    private $user = "root";
    private $pass = "";
    private $db_name = "toko_db";
    protected $dbh;

    public function __construct() {
        // Ambil data dari konfigurasi .env
        $this->host    = getenv('DB_HOST');
        $this->user    = getenv('DB_USER');
        $this->pass    = getenv('DB_PASS');
        $this->db_name = getenv('DB_NAME');

        try {
            $this->dbh = new PDO("mysql:host=$this->host;dbname=$this->db_name", $this->user, $this->pass);
            $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            die($e->getMessage());
        }
    }
}
