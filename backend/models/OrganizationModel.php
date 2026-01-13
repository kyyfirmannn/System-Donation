<?php
// models/OrganizationModel.php

require_once __DIR__ .'/../config/database.php';

class OrganizationModel {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    // Get all organizations
    public function getAllOrganizations() {
        $conn = $this->db->getConnection();
        
        $sql = "SELECT * FROM organisasi ORDER BY nama_organisasi ASC";
        $result = $conn->query($sql);
        
        $organizations = [];
        while ($row = $result->fetch_assoc()) {
            $organizations[] = $row;
        }
        
        return $organizations;
    }

    // Get organization by ID
    public function getOrganizationById($id) {
        $conn = $this->db->getConnection();
        $id = (int)$id;
        
        $sql = "SELECT * FROM organisasi WHERE id_organisasi = $id";
        $result = $conn->query($sql);
        
        if ($result->num_rows === 1) {
            return $result->fetch_assoc();
        }
        
        return null;
    }

    // Create new organization
    public function createOrganization($data) {
        $conn = $this->db->getConnection();
        
        $nama_organisasi = $conn->real_escape_string($data['nama_organisasi']);
        $alamat = $conn->real_escape_string($data['alamat']);
        $email_kontak = $conn->real_escape_string($data['email_kontak']);
        $no_kontak = $conn->real_escape_string($data['no_kontak']);
        
        $sql = "INSERT INTO organisasi (nama_organisasi, alamat, email_kontak, no_kontak) 
                VALUES ('$nama_organisasi', '$alamat', '$email_kontak', '$no_kontak')";
        
        if ($conn->query($sql) === TRUE) {
            return $conn->insert_id;
        }
        
        return false;
    }

    // Update organization
    public function updateOrganization($id, $data) {
        $conn = $this->db->getConnection();
        $id = (int)$id;
        
        $nama_organisasi = $conn->real_escape_string($data['nama_organisasi']);
        $alamat = $conn->real_escape_string($data['alamat']);
        $email_kontak = $conn->real_escape_string($data['email_kontak']);
        $no_kontak = $conn->real_escape_string($data['no_kontak']);
        
        $sql = "UPDATE organisasi SET 
                nama_organisasi = '$nama_organisasi', 
                alamat = '$alamat', 
                email_kontak = '$email_kontak', 
                no_kontak = '$no_kontak' 
                WHERE id_organisasi = $id";
        
        return $conn->query($sql);
    }

    // Delete organization
    public function deleteOrganization($id) {
        $conn = $this->db->getConnection();
        $id = (int)$id;
        
        $sql = "DELETE FROM organisasi WHERE id_organisasi = $id";
        return $conn->query($sql);
    }
}
?>