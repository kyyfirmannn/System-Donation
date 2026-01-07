<?php
require_once __DIR__ . '/../../backend/models/UserModel.php';
require_once __DIR__ . '/../../backend/config/session.php';

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';
$amount = $_POST['amount'] ?? null;
$redirect = $_POST['redirect'] ?? 'index.php';

$um = new UserModel();
$user = $um->login($email, $password);

if ($user) {
    Session::set('user_id', $user['id_pengguna']);
    Session::set('user_name', $user['nama_pengguna']);
    Session::set('role', $user['role']);

    if ($amount) {
        header('Location: pembayaran.php?nominal=' . urlencode($amount));
        exit;
    }

    header('Location: ' . $redirect);
    exit;
} else {
    header('Location: donatur.php?error=1&redirect=' . urlencode($redirect));
    exit;
}

?>
