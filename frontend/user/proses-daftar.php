<?php
require_once __DIR__ . '/../../backend/models/UserModel.php';

$redirect = $_POST['redirect'] ?? 'index.php';

$data = [
    'nama_pengguna' => $_POST['nama'] ?? '',
    'email' => $_POST['email'] ?? '',
    'password' => $_POST['password'] ?? ''
];

$um = new UserModel();
if ($um->register($data)) {
    header('Location: donatur.php?registered=1&redirect=' . urlencode($redirect));
    exit;
} else {
    header('Location: daftar.php?error=1&redirect=' . urlencode($redirect));
    exit;
}

?>
