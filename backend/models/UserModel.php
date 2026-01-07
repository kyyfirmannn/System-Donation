<?php
// models/UserModel.php

require_once __DIR__.'/../config/database.php';

class UserModel {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    // Login user
    public function login($email, $password) {
        $conn = $this->db->getConnection();
        $email = $conn->real_escape_string($email);
        
        $sql = "SELECT * FROM users WHERE email = '$email'";
        $result = $conn->query($sql);
        
        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            // Verifikasi password (plain text untuk demo)
            if ($password === $user['password']) {
                return $user;
            }
        }
        return false;
    }

    // Daftar user baru
    public function register($data) {
        $conn = $this->db->getConnection();
        
        $nama = $conn->real_escape_string($data['nama_pengguna']);
        $email = $conn->real_escape_string($data['email']);
        $password = $conn->real_escape_string($data['password']);
        $alamat = $conn->real_escape_string($data['alamat'] ?? '');
        $no_hp = $conn->real_escape_string($data['no_hp'] ?? '');
        $role = 'donatur'; // Default role

        $sql = "INSERT INTO users (nama_pengguna, email, password, role, alamat, no_hp) 
                VALUES ('$nama', '$email', '$password', '$role', '$alamat', '$no_hp')";
        
        return $conn->query($sql);
    }

    // Get user by ID
    public function getUserById($id) {
        $conn = $this->db->getConnection();
        $id = (int)$id;
        
        $sql = "SELECT * FROM users WHERE id_pengguna = $id";
        $result = $conn->query($sql);
        
        return $result->fetch_assoc();
    }

    // Update user
    public function updateUser($id, $data) {
        $conn = $this->db->getConnection();
        $id = (int)$id;
        
        $updates = [];
        foreach ($data as $key => $value) {
            $value = $conn->real_escape_string($value);
            $updates[] = "$key = '$value'";
        }
        
        if (empty($updates)) return false;
        
        $sql = "UPDATE users SET " . implode(', ', $updates) . " WHERE id_pengguna = $id";
        return $conn->query($sql);
    }

    // Get all donors (for admin)
    public function getAllDonors() {
        $conn = $this->db->getConnection();
        
        $sql = "SELECT * FROM users WHERE role = 'donatur' ORDER BY dibuat_pada DESC";
        $result = $conn->query($sql);
        
        $donors = [];
        while ($row = $result->fetch_assoc()) {
            $donors[] = $row;
        }
        
        return $donors;
    }

    // Count total donors
    public function countDonors() {
        $conn = $this->db->getConnection();
        
        $sql = "SELECT COUNT(*) as total FROM users WHERE role = 'donatur'";
        $result = $conn->query($sql);
        
        return $result->fetch_assoc()['total'];
    }
}
?>