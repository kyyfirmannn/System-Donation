<?php
// frontend/admin/donor.php
require_once __DIR__ . '/../../backend/config/database.php';
require_once __DIR__ . '/../../backend/models/UserModel.php';
require_once __DIR__ . '/../../backend/models/DonationModel.php';

$userModel = new UserModel();
$donationModel = new DonationModel();

if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    if ($userModel->deleteDonor($id)) {
        $_SESSION['success'] = "Donor berhasil dihapus";
    } else {
        $_SESSION['error'] = "Tidak dapat menghapus donor yang memiliki riwayat donasi";
    }
    header('Location: donor.php');
    exit;
}

$search = $_GET['search'] ?? '';
$filter = $_GET['filter'] ?? 'all';

$donors = $userModel->getAllDonorsWithStats();

if ($search) {
    $donors = array_filter($donors, function($donor) use ($search) {
        return stripos($donor['nama_pengguna'], $search) !== false ||
               stripos($donor['email'], $search) !== false ||
               stripos($donor['no_hp'], $search) !== false;
    });
}

if ($filter === 'has_donations') {
    $donors = array_filter($donors, fn($d) => $d['donation_count'] > 0);
} elseif ($filter === 'no_donations') {
    $donors = array_filter($donors, fn($d) => $d['donation_count'] == 0);
}

$donors = array_values($donors);

$totalDonations = 0;
$totalAmount = 0;
foreach ($donors as $donor) {
    $totalDonations += $donor['donation_count'];
    $totalAmount += $donor['donation_total'];
}

function formatRupiah($angka) {
    return 'Rp ' . number_format($angka, 0, ',', '.');
}

function getInitials($name) {
    $words = explode(' ', $name);
    $initials = '';
    foreach ($words as $word) {
        $initials .= strtoupper(substr($word, 0, 1));
    }
    return substr($initials, 0, 2);
}

function getAvatarColor($name) {
    $colors = ['bg-primary','bg-success','bg-danger','bg-warning','bg-info','bg-secondary','bg-dark'];
    return $colors[crc32($name) % count($colors)];
}
?>

<?php include 'header.php'; ?>

<!-- FIXED MAIN LAYOUT -->
<main class="col-md-9 col-lg-10 px-md-4 py-4">

<div class="container-xl">

    <!-- Alert -->
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?= $_SESSION['success']; unset($_SESSION['success']); ?>
            <button class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <?= $_SESSION['error']; unset($_SESSION['error']); ?>
            <button class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0">Manajemen Donor</h2>
            <p class="text-muted">Kelola data donor dan kontributor</p>
        </div>
        <button class="btn btn-dark" id="addDonorBtn">
            <i class="bi bi-plus-lg me-1"></i> Tambah Donor
        </button>
    </div>

    <!-- Stats -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card shadow-sm text-center p-3">
                <small class="text-muted">Total Donor</small>
                <h3 class="fw-bold"><?= $userModel->countDonors(); ?></h3>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm text-center p-3">
                <small class="text-muted">Total Donasi</small>
                <h3 class="fw-bold"><?= $totalDonations; ?></h3>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm text-center p-3">
                <small class="text-muted">Total Kontribusi</small>
                <h3 class="fw-bold"><?= formatRupiah($totalAmount); ?></h3>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Donor</th>
                        <th>Kontak</th>
                        <th>Bergabung</th>
                        <th>Statistik</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($donors as $donor): ?>
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle <?= getAvatarColor($donor['nama_pengguna']); ?> text-white me-3 d-flex justify-content-center align-items-center"
                                     style="width:40px;height:40px;">
                                    <?= getInitials($donor['nama_pengguna']); ?>
                                </div>
                                <strong><?= htmlspecialchars($donor['nama_pengguna']); ?></strong>
                            </div>
                        </td>
                        <td><?= htmlspecialchars($donor['email']); ?></td>
                        <td><?= date('d M Y', strtotime($donor['dibuat_pada'])); ?></td>
                        <td><?= formatRupiah($donor['donation_total']); ?></td>
                        <td class="text-end">
                            <button class="btn btn-sm btn-light edit-donor-btn" data-id="<?= $donor['id_pengguna']; ?>">
                                <i class="bi bi-pencil"></i>
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>
</main>
