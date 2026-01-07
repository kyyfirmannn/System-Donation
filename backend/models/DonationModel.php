<?php
// models/DonationModel.php

require_once __DIR__.'/../config/database.php';
require_once __DIR__ . '/CampaignModel.php';

class DonationModel {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    // Create new donation
    public function createDonation($data) {
        $conn = $this->db->getConnection();
        
        $id_pengguna = (int)$data['id_pengguna'];
        $id_kampanye = (int)$data['id_kampanye'];
        $jumlah_donasi = (float)$data['jumlah_donasi'];
        $metode_pembayaran = $conn->real_escape_string($data['metode_pembayaran']);
        $status = $conn->real_escape_string($data['status'] ?? 'pending');
        
        $sql = "INSERT INTO donasi (id_pengguna, id_kampanye, jumlah_donasi, metode_pembayaran, status) 
                VALUES ($id_pengguna, $id_kampanye, $jumlah_donasi, '$metode_pembayaran', '$status')";
        
        if ($conn->query($sql)) {
            $donation_id = $conn->insert_id;
            
            // Update campaign funds if donation is successful
            if ($status === 'berhasil') {
                $campaignModel = new CampaignModel();
                $campaignModel->updateCollectedFunds($id_kampanye, $jumlah_donasi);
            }
            
            return $donation_id;
        }
        
        return false;
    }

    // Get donation by ID
    public function getDonationById($id) {
        $conn = $this->db->getConnection();
        $id = (int)$id;
        
        $sql = "SELECT d.*, u.nama_pengguna, k.judul_kampanye 
                FROM donasi d
                JOIN users u ON d.id_pengguna = u.id_pengguna
                JOIN kampanye k ON d.id_kampanye = k.id_kampanye
                WHERE d.id_donasi = $id";
        
        $result = $conn->query($sql);
        
        if ($result->num_rows === 1) {
            return $result->fetch_assoc();
        }
        
        return null;
    }

    // Get donations by user
    public function getDonationsByUser($user_id) {
        $conn = $this->db->getConnection();
        $user_id = (int)$user_id;
        
        $sql = "SELECT d.*, k.judul_kampanye, o.nama_organisasi 
                FROM donasi d
                JOIN kampanye k ON d.id_kampanye = k.id_kampanye
                JOIN organisasi o ON k.id_organisasi = o.id_organisasi
                WHERE d.id_pengguna = $user_id
                ORDER BY d.tgl_donasi DESC";
        
        $result = $conn->query($sql);
        
        $donations = [];
        while ($row = $result->fetch_assoc()) {
            $donations[] = $row;
        }
        
        return $donations;
    }

    // Get all donations (for admin)
    public function getAllDonations($limit = null) {
        $conn = $this->db->getConnection();
        
        $sql = "SELECT d.*, u.nama_pengguna, k.judul_kampanye 
                FROM donasi d
                JOIN users u ON d.id_pengguna = u.id_pengguna
                JOIN kampanye k ON d.id_kampanye = k.id_kampanye
                ORDER BY d.tgl_donasi DESC";
        
        if ($limit) {
            $sql .= " LIMIT " . (int)$limit;
        }
        
        $result = $conn->query($sql);
        
        $donations = [];
        while ($row = $result->fetch_assoc()) {
            $donations[] = $row;
        }
        
        return $donations;
    }

    // Update donation status
    public function updateDonationStatus($id, $status) {
        $conn = $this->db->getConnection();
        $id = (int)$id;
        $status = $conn->real_escape_string($status);
        
        // Get donation details first
        $donation = $this->getDonationById($id);
        
        $sql = "UPDATE donasi SET status = '$status' WHERE id_donasi = $id";
        
        if ($conn->query($sql)) {
            // If status changed to successful, update campaign funds
            if ($status === 'berhasil' && $donation && $donation['status'] !== 'berhasil') {
                $campaignModel = new CampaignModel();
                $campaignModel->updateCollectedFunds($donation['id_kampanye'], $donation['jumlah_donasi']);
            }
            return true;
        }
        
        return false;
    }

    // Get donation statistics
    public function getStatistics() {
        $conn = $this->db->getConnection();
        
        $stats = [];
        
        // Total donations
        $sql = "SELECT SUM(jumlah_donasi) as total FROM donasi WHERE status = 'berhasil'";
        $result = $conn->query($sql);
        $stats['total_donations'] = $result->fetch_assoc()['total'] ?? 0;
        
        // Total donors
        $sql = "SELECT COUNT(DISTINCT id_pengguna) as total FROM donasi WHERE status = 'berhasil'";
        $result = $conn->query($sql);
        $stats['total_donors'] = $result->fetch_assoc()['total'] ?? 0;
        
        // Monthly donations
        $sql = "SELECT 
                DATE_FORMAT(tgl_donasi, '%Y-%m') as month,
                SUM(jumlah_donasi) as total
                FROM donasi 
                WHERE status = 'berhasil'
                GROUP BY DATE_FORMAT(tgl_donasi, '%Y-%m')
                ORDER BY month DESC
                LIMIT 6";
        $result = $conn->query($sql);
        
        $stats['monthly'] = [];
        while ($row = $result->fetch_assoc()) {
            $stats['monthly'][] = $row;
        }
        
        return $stats;
    }

    // Count donations by status
    public function countDonations($status = null) {
        $conn = $this->db->getConnection();
        
        $sql = "SELECT COUNT(*) as total FROM donasi";
        
        if ($status) {
            $status = $conn->real_escape_string($status);
            $sql .= " WHERE status = '$status'";
        }
        
        $result = $conn->query($sql);
        
        return $result->fetch_assoc()['total'];
    }
}
?>