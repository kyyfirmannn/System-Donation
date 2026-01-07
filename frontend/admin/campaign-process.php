<?php
// frontend/admin/campaign-process.php
session_start();
require_once __DIR__ . '/../../backend/config/database.php';
require_once __DIR__ . '/../../backend/models/CampaignModel.php';

$campaignModel = new CampaignModel();

// Set JSON header
header('Content-Type: application/json');

// Handle get campaign data
if (isset($_GET['get_campaign']) && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $campaign = $campaignModel->getCampaignById($id);
    
    if ($campaign) {
        echo json_encode(['success' => true, 'campaign' => $campaign]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Campaign tidak ditemukan']);
    }
    exit;
}

// Handle add campaign
if (isset($_GET['action']) && $_GET['action'] === 'add' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'judul_kampanye' => trim($_POST['judul_kampanye']),
        'deskripsi' => trim($_POST['deskripsi']),
        'target_dana' => (float)str_replace('.', '', $_POST['target_dana']),
        'dana_terkumpul' => (float)str_replace('.', '', $_POST['dana_terkumpul'] ?? '0'),
        'tgl_mulai' => $_POST['tgl_mulai'],
        'tgl_selesai' => $_POST['tgl_selesai'],
        'id_organisasi' => (int)$_POST['id_organisasi'],
        'status' => $_POST['status'],
        'dibuat_oleh' => 1
    ];
    
    // Validation
    if (empty($data['judul_kampanye']) || empty($data['deskripsi'])) {
        echo json_encode(['success' => false, 'message' => 'Judul dan deskripsi harus diisi']);
        exit;
    }
    
    if ($data['target_dana'] <= 0) {
        echo json_encode(['success' => false, 'message' => 'Target dana harus lebih dari 0']);
        exit;
    }
    
    if (strtotime($data['tgl_selesai']) <= strtotime($data['tgl_mulai'])) {
        echo json_encode(['success' => false, 'message' => 'Tanggal selesai harus setelah tanggal mulai']);
        exit;
    }
    
    // Create campaign
    if ($campaignModel->createCampaign($data)) {
        echo json_encode(['success' => true, 'message' => 'Campaign berhasil ditambahkan']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Gagal menambahkan campaign']);
    }
    exit;
}

// Handle edit campaign
if (isset($_GET['action']) && $_GET['action'] === 'edit' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)$_POST['id_kampanye'];
    
    $data = [
        'judul_kampanye' => trim($_POST['judul_kampanye']),
        'deskripsi' => trim($_POST['deskripsi']),
        'target_dana' => (float)str_replace('.', '', $_POST['target_dana']),
        'dana_terkumpul' => (float)str_replace('.', '', $_POST['dana_terkumpul']),
        'tgl_mulai' => $_POST['tgl_mulai'],
        'tgl_selesai' => $_POST['tgl_selesai'],
        'id_organisasi' => (int)$_POST['id_organisasi'],
        'status' => $_POST['status']
    ];
    
    // Validation
    if (empty($data['judul_kampanye']) || empty($data['deskripsi'])) {
        echo json_encode(['success' => false, 'message' => 'Judul dan deskripsi harus diisi']);
        exit;
    }
    
    if ($data['target_dana'] <= 0) {
        echo json_encode(['success' => false, 'message' => 'Target dana harus lebih dari 0']);
        exit;
    }
    
    if (strtotime($data['tgl_selesai']) <= strtotime($data['tgl_mulai'])) {
        echo json_encode(['success' => false, 'message' => 'Tanggal selesai harus setelah tanggal mulai']);
        exit;
    }
    
    // Update campaign
    if ($campaignModel->updateCampaign($id, $data)) {
        echo json_encode(['success' => true, 'message' => 'Campaign berhasil diperbarui']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Gagal memperbarui campaign']);
    }
    exit;
}

echo json_encode(['success' => false, 'message' => 'Aksi tidak valid']);
?>