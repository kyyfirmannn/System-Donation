<?php
require_once __DIR__ . '/../../backend/config/session.php';
require_once __DIR__ . '/../../backend/models/DonationModel.php';
require_once __DIR__ . '/../../backend/models/UserModel.php';
require_once __DIR__ . '/../../backend/models/User.php';

Session::start();

$nominal = $_POST['nominal'] ?? ($_REQUEST['nominal'] ?? 0);
$id_kampanye = $_POST['id_kampanye'] ?? 1;
$metode = $_POST['metode'] ?? ($_POST['metode_pembayaran'] ?? 'Transfer Bank');

$user_id = Session::get('user_id');
$um = new UserModel();

// Jika belum login, coba temukan atau buat user berdasarkan email yang dikirim
if (!$user_id) {
    $email = $_POST['email'] ?? null;
    $nama = $_POST['nama'] ?? 'Anonymous';

    if ($email) {
        $found = User::findByEmail($email);
        if ($found && isset($found['id_pengguna'])) {
            $user_id = $found['id_pengguna'];
        } else {
            // buat user sementara dengan password acak
            $password = bin2hex(random_bytes(4));
            $um->register(['nama_pengguna' => $nama, 'email' => $email, 'password' => $password]);
            $created = User::findByEmail($email);
            $user_id = $created['id_pengguna'] ?? null;
        }
    }
}

if (!$user_id) {
    // tidak dapat menentukan user
    header('Location: donatur.php?error=login_required');
    exit;
}

$donationModel = new DonationModel();
$data = [
    'id_pengguna' => $user_id,
    'id_kampanye' => $id_kampanye,
    'jumlah_donasi' => $nominal,
    'metode_pembayaran' => $metode,
    'status' => 'berhasil'
];

$donation_id = $donationModel->createDonation($data);

if ($donation_id) {
    header('Location: donasi-berhasil.php?nominal=' . urlencode($nominal));
    exit;
} else {
    header('Location: pembayaran.php?error=1');
    exit;
}

?>
