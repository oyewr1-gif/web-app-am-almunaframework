<?php

class UserModel extends Model {
    // Tentukan nama tabel di database
    protected $table = 'users';

    // Membuat fungsi spesifik yang tidak dicakup oleh CRUD generik
    public function getUserByEmail(string $email) 
    {
        $this->db->query("SELECT * FROM {$this->table} WHERE email = :email");
        
        // Data di-bind secara aman. SQL Injection otomatis terblokir di sini.
        $this->db->bind(':email', $email); 
        
        return $this->db->single();
    }

    public function registerUser(array $data) 
    {
        $query = "INSERT INTO {$this->table} (nama, email, password) VALUES (:nama, :email, :password)";
        
        $this->db->query($query);
        $this->db->bind(':nama', $data['nama']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':password', password_hash($data['password'], PASSWORD_DEFAULT)); // Hash password demi keamanan
        
        return $this->db->execute();
    }

}