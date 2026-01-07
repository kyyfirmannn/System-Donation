<?php
// config/database.php

class Database {
    private $host = "localhost";
    private $username = "root";
    private $password = "";
    private $database = "db_donasi";
    private $conn;

    public function __construct() {
        $this->conn = new mysqli($this->host, $this->username, $this->password, $this->database);
        
        if ($this->conn->connect_error) {
            die("Koneksi gagal: " . $this->conn->connect_error);
        }
        
        $this->conn->set_charset("utf8");
    }

    public function getConnection() {
        return $this->conn;
    }

    public function closeConnection() {
        if ($this->conn) {
            $this->conn->close();
        }
    }

    // Method untuk query
    public function query($sql) {
        return $this->conn->query($sql);
    }

    public function escape($string) {
        return $this->conn->real_escape_string($string);
    }

    public function getInsertId() {
        return $this->conn->insert_id;
    }
}

// Buat instance global jika diperlukan
$db = new Database();
$conn = $db->getConnection();
?>