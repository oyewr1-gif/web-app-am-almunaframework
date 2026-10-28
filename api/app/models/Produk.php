<?php
# require_once '../app/core/Database.php';

class Produk extends Database {
    public function getAll() {
        $stmt = $this->dbh->prepare("SELECT * FROM produk");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->dbh->prepare("SELECT * FROM produk WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $stmt = $this->dbh->prepare("INSERT INTO produk (nama, harga) VALUES (:nama, :harga)");
        $stmt->bindParam(':nama', $data['nama']);
        $stmt->bindParam(':harga', $data['harga']);
        return $stmt->execute();
    }
}
