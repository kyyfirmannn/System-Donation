<?php
// backend/models/CampaignModel.php

require_once __DIR__ . '/../config/database.php';

class CampaignModel {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    // Get all campaigns
    public function getAllCampaigns($limit = null) {
        $conn = $this->db->getConnection();
        
        $sql = "SELECT k.*, o.nama_organisasi 
                FROM kampanye k 
                JOIN organisasi o ON k.id_organisasi = o.id_organisasi 
                ORDER BY k.dibuat_pada DESC";
        
        if ($limit) {
            $sql .= " LIMIT " . (int)$limit;
        }
        
        $result = $conn->query($sql);
        
        $campaigns = [];
        while ($row = $result->fetch_assoc()) {
            $campaigns[] = $row;
        }
        
        return $campaigns;
    }

    // Get active campaigns
    public function getActiveCampaigns() {
        $conn = $this->db->getConnection();
        
        $sql = "SELECT k.*, o.nama_organisasi 
                FROM kampanye k 
                JOIN organisasi o ON k.id_organisasi = o.id_organisasi 
                WHERE k.status = 'aktif' 
                ORDER BY k.dibuat_pada DESC";
        
        $result = $conn->query($sql);
        
        $campaigns = [];
        while ($row = $result->fetch_assoc()) {
            $campaigns[] = $row;
        }
        
        return $campaigns;
    }

    // Get campaign by ID
    public function getCampaignById($id) {
        $conn = $this->db->getConnection();
        $id = (int)$id;
        
        $sql = "SELECT k.*, o.nama_organisasi, o.alamat, o.email_kontak, o.no_kontak 
                FROM kampanye k 
                JOIN organisasi o ON k.id_organisasi = o.id_organisasi 
                WHERE k.id_kampanye = $id";
        
        $result = $conn->query($sql);
        
        if ($result->num_rows === 1) {
            return $result->fetch_assoc();
        }
        
        return null;
    }

    // Create new campaign
    public function createCampaign($data) {
        $conn = $this->db->getConnection();
        
        $judul = $conn->real_escape_string($data['judul_kampanye']);
        $deskripsi = $conn->real_escape_string($data['deskripsi']);
        $target_dana = (float)$data['target_dana'];
        $dana_terkumpul = isset($data['dana_terkumpul']) ? (float)$data['dana_terkumpul'] : 0;
        $tgl_mulai = $conn->real_escape_string($data['tgl_mulai']);
        $tgl_selesai = $conn->real_escape_string($data['tgl_selesai']);
        $id_organisasi = (int)$data['id_organisasi'];
        $dibuat_oleh = isset($data['dibuat_oleh']) ? (int)$data['dibuat_oleh'] : 1;
        $status = isset($data['status']) ? $conn->real_escape_string($data['status']) : 'aktif';
        
        $sql = "INSERT INTO kampanye (judul_kampanye, deskripsi, target_dana, dana_terkumpul, 
                tgl_mulai, tgl_selesai, id_organisasi, dibuat_oleh, status) 
                VALUES ('$judul', '$deskripsi', $target_dana, $dana_terkumpul, 
                '$tgl_mulai', '$tgl_selesai', $id_organisasi, $dibuat_oleh, '$status')";
        
        return $conn->query($sql);
    }

    // Update campaign
    public function updateCampaign($id, $data) {
        $conn = $this->db->getConnection();
        $id = (int)$id;
        
        $updates = [];
        foreach ($data as $key => $value) {
            if ($key === 'target_dana' || $key === 'dana_terkumpul') {
                $updates[] = "$key = " . (float)$value;
            } elseif ($key === 'id_organisasi') {
                $updates[] = "$key = " . (int)$value;
            } else {
                $value = $conn->real_escape_string($value);
                $updates[] = "$key = '$value'";
            }
        }
        
        if (empty($updates)) return false;
        
        $sql = "UPDATE kampanye SET " . implode(', ', $updates) . " WHERE id_kampanye = $id";
        return $conn->query($sql);
    }

    // Update collected funds
    public function updateCollectedFunds($id_kampanye, $jumlah) {
        $conn = $this->db->getConnection();
        $id = (int)$id_kampanye;
        $jumlah = (float)$jumlah;
        
        $sql = "UPDATE kampanye 
                SET dana_terkumpul = dana_terkumpul + $jumlah 
                WHERE id_kampanye = $id";
        
        return $conn->query($sql);
    }

    // Count campaigns by status
    public function countCampaigns($status = null) {
        $conn = $this->db->getConnection();
        
        $sql = "SELECT COUNT(*) as total FROM kampanye";
        
        if ($status) {
            $status = $conn->real_escape_string($status);
            $sql .= " WHERE status = '$status'";
        }
        
        $result = $conn->query($sql);
        
        return $result->fetch_assoc()['total'];
    }

    // Get top campaigns
    public function getTopCampaigns($limit = 5) {
        $conn = $this->db->getConnection();
        $limit = (int)$limit;
        
        $sql = "SELECT k.*, o.nama_organisasi,
                (k.dana_terkumpul / k.target_dana * 100) as progress
                FROM kampanye k 
                JOIN organisasi o ON k.id_organisasi = o.id_organisasi 
                WHERE k.status = 'aktif'
                ORDER BY k.dana_terkumpul DESC 
                LIMIT $limit";
        
        $result = $conn->query($sql);
        
        $campaigns = [];
        while ($row = $result->fetch_assoc()) {
            $campaigns[] = $row;
        }
        
        return $campaigns;
    }

    // Get campaigns with donor count for display
    public function getCampaignsForDisplay($limit = 3) {
        $conn = $this->db->getConnection();
        $limit = (int)$limit;
        
        $sql = "SELECT k.*, o.nama_organisasi,
                COUNT(DISTINCT d.id_pengguna) as donor_count,
                DATEDIFF(k.tgl_selesai, CURDATE()) as days_left,
                (k.dana_terkumpul / k.target_dana * 100) as progress
                FROM kampanye k 
                JOIN organisasi o ON k.id_organisasi = o.id_organisasi 
                LEFT JOIN donasi d ON k.id_kampanye = d.id_kampanye AND d.status = 'berhasil'
                WHERE k.status = 'aktif' AND k.tgl_selesai >= CURDATE()
                GROUP BY k.id_kampanye
                ORDER BY k.dibuat_pada DESC 
                LIMIT $limit";
        
        $result = $conn->query($sql);
        
        $campaigns = [];
        while ($row = $result->fetch_assoc()) {
            $campaigns[] = $row;
        }
        
        return $campaigns;
    }

    // Get recent campaigns
    public function getRecentCampaigns($limit = 2) {
        $conn = $this->db->getConnection();
        $limit = (int)$limit;
        
        $sql = "SELECT k.*, o.nama_organisasi
                FROM kampanye k 
                JOIN organisasi o ON k.id_organisasi = o.id_organisasi 
                ORDER BY k.dibuat_pada DESC 
                LIMIT $limit";
        
        $result = $conn->query($sql);
        
        $campaigns = [];
        while ($row = $result->fetch_assoc()) {
            $campaigns[] = $row;
        }
        
        return $campaigns;
    }
}
?>