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
        $role = $data['role'] ?? 'donatur';

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
        
        if ($result->num_rows === 1) {
            return $result->fetch_assoc();
        }
        return null;
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

    // Get all donors (for admin) - TANPA DUPLIKASI
    public function getAllDonors() {
        $conn = $this->db->getConnection();
        
        $sql = "SELECT DISTINCT u.* 
                FROM users u
                WHERE u.role = 'donatur' 
                ORDER BY u.nama_pengguna ASC";
        
        $result = $conn->query($sql);
        
        $donors = [];
        while ($row = $result->fetch_assoc()) {
            $donors[] = $row;
        }
        
        return $donors;
    }

    // Get all donors WITH donation statistics - UNTUK HAPUS DUPLIKASI
    public function getAllDonorsWithStats() {
        $conn = $this->db->getConnection();
        
        $sql = "SELECT 
                    u.id_pengguna,
                    u.nama_pengguna,
                    u.email,
                    u.alamat,
                    u.no_hp,
                    u.dibuat_pada,
                    COUNT(d.id_donasi) as donation_count,
                    COALESCE(SUM(d.jumlah_donasi), 0) as donation_total
                FROM users u
                LEFT JOIN donasi d ON u.id_pengguna = d.id_pengguna 
                    AND d.status = 'berhasil'
                WHERE u.role = 'donatur'
                GROUP BY u.id_pengguna
                ORDER BY u.dibuat_pada DESC";
        
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
        
        $sql = "SELECT COUNT(DISTINCT id_pengguna) as total 
                FROM users 
                WHERE role = 'donatur'";
        
        $result = $conn->query($sql);
        
        return $result->fetch_assoc()['total'];
    }

    // Check if email already exists
    public function emailExists($email, $excludeId = null) {
        $conn = $this->db->getConnection();
        $email = $conn->real_escape_string($email);
        
        $sql = "SELECT id_pengguna FROM users WHERE email = '$email'";
        
        if ($excludeId) {
            $excludeId = (int)$excludeId;
            $sql .= " AND id_pengguna != $excludeId";
        }
        
        $result = $conn->query($sql);
        return $result->num_rows > 0;
    }

    // Get top donors by donation amount
    public function getTopDonors($limit = 5) {
        $conn = $this->db->getConnection();
        $limit = (int)$limit;
        
        $sql = "SELECT 
                    u.id_pengguna,
                    u.nama_pengguna,
                    u.email,
                    COUNT(d.id_donasi) as donation_count,
                    COALESCE(SUM(d.jumlah_donasi), 0) as donation_total
                FROM users u
                LEFT JOIN donasi d ON u.id_pengguna = d.id_pengguna 
                    AND d.status = 'berhasil'
                WHERE u.role = 'donatur'
                GROUP BY u.id_pengguna
                HAVING donation_total > 0
                ORDER BY donation_total DESC
                LIMIT $limit";
        
        $result = $conn->query($sql);
        
        $donors = [];
        while ($row = $result->fetch_assoc()) {
            $donors[] = $row;
        }
        
        return $donors;
    }

    // Get new donors (last X days)
    public function getNewDonors($days = 30, $limit = 5) {
        $conn = $this->db->getConnection();
        $days = (int)$days;
        $limit = (int)$limit;
        
        $sql = "SELECT 
                    u.*,
                    (SELECT COUNT(*) FROM donasi d 
                     WHERE d.id_pengguna = u.id_pengguna 
                     AND d.status = 'berhasil') as donation_count
                FROM users u
                WHERE u.role = 'donatur'
                AND u.dibuat_pada >= DATE_SUB(NOW(), INTERVAL $days DAY)
                ORDER BY u.dibuat_pada DESC
                LIMIT $limit";
        
        $result = $conn->query($sql);
        
        $donors = [];
        while ($row = $result->fetch_assoc()) {
            $donors[] = $row;
        }
        
        return $donors;
    }

    // Delete donor (only if no donations)
    public function deleteDonor($id) {
        $conn = $this->db->getConnection();
        $id = (int)$id;
        
        // Check if donor has donations
        $checkSql = "SELECT COUNT(*) as total FROM donasi WHERE id_pengguna = $id";
        $checkResult = $conn->query($checkSql);
        $hasDonations = $checkResult->fetch_assoc()['total'] > 0;
        
        if ($hasDonations) {
            return false; // Cannot delete donor with donations
        }
        
        $sql = "DELETE FROM users WHERE id_pengguna = $id AND role = 'donatur'";
        return $conn->query($sql);
    }
}
?>