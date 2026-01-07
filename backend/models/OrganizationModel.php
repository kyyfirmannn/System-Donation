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
}
?>