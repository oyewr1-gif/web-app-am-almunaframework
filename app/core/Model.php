<?php

class Model 
{
    protected $db;
    protected $table; // Nama tabel akan didefinisikan di Model anak

    public function __construct() 
    {
        // Otomatis menginisialisasi koneksi database saat Model dipanggil
        $this->db = new Database();
    }

    /**
     * CRUD Generik: Ambil semua data dari tabel terkait
     */
    public function getAll() 
    {
        $this->db->query("SELECT * FROM {$this->table}");
        return $this->db->resultSet();
    }

    /**
     * CRUD Generik: Cari data berdasarkan ID secara aman
     */
    public function find(int $id) 
    {
        $this->db->query("SELECT * FROM {$this->table} WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    /**
     * CRUD Generik: Hapus data berdasarkan ID secara aman
     */
    public function delete(int $id) 
    {
        $this->db->query("DELETE FROM {$this->table} WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }
}
