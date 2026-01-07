<?php
// frontend/admin/donor-process.php
session_start();
require_once __DIR__ . '/../../backend/config/database.php';
require_once __DIR__ . '/../../backend/models/UserModel.php';

$userModel = new UserModel();

// Set JSON header
header('Content-Type: application/json');

// Handle get donor data
if (isset($_GET['get_donor']) && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $donor = $userModel->getUserById($id);
    
    if ($donor && $donor['role'] === 'donatur') {
        echo json_encode(['success' => true, 'donor' => $donor]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Donor tidak ditemukan']);
    }
    exit;
}

// Handle add donor
if (isset($_GET['action']) && $_GET['action'] === 'add' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'nama_pengguna' => trim($_POST['nama_pengguna']),
        'email' => trim($_POST['email']),
        'password' => trim($_POST['password']),
        'no_hp' => trim($_POST['no_hp'] ?? ''),
        'alamat' => trim($_POST['alamat'] ?? ''),
        'role' => 'donatur'
    ];
    
    // Validation
    if (empty($data['nama_pengguna']) || empty($data['email']) || empty($data['password'])) {
        echo json_encode(['success' => false, 'message' => 'Nama, email, dan password harus diisi']);
        exit;
    }
    
    if (strlen($data['password']) < 6) {
        echo json_encode(['success' => false, 'message' => 'Password minimal 6 karakter']);
        exit;
    }
    
    if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'Format email tidak valid']);
        exit;
    }
    
    // Check if email already exists
    $conn = (new Database())->getConnection();
    $email = $conn->real_escape_string($data['email']);
    $checkSql = "SELECT id_pengguna FROM users WHERE email = '$email'";
    $checkResult = $conn->query($checkSql);
    
    if ($checkResult->num_rows > 0) {
        echo json_encode(['success' => false, 'message' => 'Email sudah terdaftar']);
        exit;
    }
    
    // Create donor
    if ($userModel->register($data)) {
        echo json_encode(['success' => true, 'message' => 'Donor berhasil ditambahkan']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Gagal menambahkan donor']);
    }
    exit;
}

// Handle edit donor
if (isset($_GET['action']) && $_GET['action'] === 'edit' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)$_POST['id_pengguna'];
    
    $data = [
        'nama_pengguna' => trim($_POST['nama_pengguna']),
        'email' => trim($_POST['email']),
        'no_hp' => trim($_POST['no_hp'] ?? ''),
        'alamat' => trim($_POST['alamat'] ?? '')
    ];
    
    // Add password if provided
    if (!empty($_POST['password'])) {
        $data['password'] = trim($_POST['password']);
    }
    
    // Validation
    if (empty($data['nama_pengguna']) || empty($data['email'])) {
        echo json_encode(['success' => false, 'message' => 'Nama dan email harus diisi']);
        exit;
    }
    
    if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'Format email tidak valid']);
        exit;
    }
    
    // Check if email already exists for other user
    $conn = (new Database())->getConnection();
    $email = $conn->real_escape_string($data['email']);
    $checkSql = "SELECT id_pengguna FROM users WHERE email = '$email' AND id_pengguna != $id";
    $checkResult = $conn->query($checkSql);
    
    if ($checkResult->num_rows > 0) {
        echo json_encode(['success' => false, 'message' => 'Email sudah digunakan oleh donor lain']);
        exit;
    }
    
    // Update donor
    if ($userModel->updateUser($id, $data)) {
        echo json_encode(['success' => true, 'message' => 'Donor berhasil diperbarui']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Gagal memperbarui donor']);
    }
    exit;
}

echo json_encode(['success' => false, 'message' => 'Aksi tidak valid']);
?>