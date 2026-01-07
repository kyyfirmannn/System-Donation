<?php
// frontend/admin/donor.php
session_start();
require_once __DIR__ . '/../../backend/config/database.php';
require_once __DIR__ . '/../../backend/models/UserModel.php';
require_once __DIR__ . '/../../backend/models/DonationModel.php';

$userModel = new UserModel();
$donationModel = new DonationModel();

// Handle delete donor
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $conn = (new Database())->getConnection();
    
    // Cek apakah donor punya donasi
    $checkSql = "SELECT COUNT(*) as total FROM donasi WHERE id_pengguna = $id";
    $checkResult = $conn->query($checkSql);
    $hasDonations = $checkResult->fetch_assoc()['total'] > 0;
    
    if ($hasDonations) {
        $_SESSION['error'] = "Tidak dapat menghapus donor yang memiliki riwayat donasi";
    } else {
        $sql = "DELETE FROM users WHERE id_pengguna = $id AND role = 'donatur'";
        if ($conn->query($sql)) {
            $_SESSION['success'] = "Donor berhasil dihapus";
        } else {
            $_SESSION['error'] = "Gagal menghapus donor";
        }
    }
    header('Location: donor.php');
    exit;
}

// Handle search
$search = $_GET['search'] ?? '';
$filter = $_GET['filter'] ?? 'all'; // all, has_donations, no_donations

// Get all donors
$donors = $userModel->getAllDonors();

// Apply filters
if ($search) {
    $donors = array_filter($donors, function($donor) use ($search) {
        return stripos($donor['nama_pengguna'], $search) !== false ||
               stripos($donor['email'], $search) !== false ||
               stripos($donor['no_hp'], $search) !== false;
    });
}

// Get donation statistics for each donor
$donorStats = [];
$totalDonations = 0;
$totalAmount = 0;

foreach ($donors as &$donor) {
    $conn = (new Database())->getConnection();
    $donorId = $donor['id_pengguna'];
    
    // Count successful donations
    $sql = "SELECT COUNT(*) as count, SUM(jumlah_donasi) as total 
            FROM donasi 
            WHERE id_pengguna = $donorId AND status = 'berhasil'";
    $result = $conn->query($sql);
    $stats = $result->fetch_assoc();
    
    $donationCount = $stats['count'] ?? 0;
    $donationTotal = $stats['total'] ?? 0;
    
    $donor['donation_count'] = $donationCount;
    $donor['donation_total'] = $donationTotal;
    
    $totalDonations += $donationCount;
    $totalAmount += $donationTotal;
    
    // Apply additional filters
    if ($filter === 'has_donations' && $donationCount == 0) {
        unset($donor);
        continue;
    }
    if ($filter === 'no_donations' && $donationCount > 0) {
        unset($donor);
        continue;
    }
}

// Re-index array
$donors = array_values($donors);

// Get total donors count
$totalDonors = count($donors);

// Helper functions
function formatRupiah($angka) {
    if ($angka >= 1000000000) {
        return 'Rp ' . number_format($angka / 1000000000, 1, ',', '.') . 'M';
    } elseif ($angka >= 1000000) {
        return 'Rp ' . number_format($angka / 1000000, 1, ',', '.') . 'jt';
    } elseif ($angka >= 1000) {
        return 'Rp ' . number_format($angka / 1000, 1, ',', '.') . 'rb';
    }
    return 'Rp ' . number_format($angka, 0, ',', '.');
}

function getInitials($name) {
    $words = explode(' ', $name);
    $initials = '';
    foreach ($words as $word) {
        if (!empty($word)) {
            $initials .= strtoupper(substr($word, 0, 1));
        }
    }
    return substr($initials, 0, 2);
}

function getAvatarColor($name) {
    $colors = [
        'bg-primary', 'bg-success', 'bg-danger', 'bg-warning', 
        'bg-info', 'bg-dark', 'bg-secondary', 'bg-primary'
    ];
    $hash = crc32($name) % count($colors);
    return $colors[$hash];
}
?>

<?php include 'header.php'; ?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
    <!-- Alert Messages -->
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0">Manajemen Donor</h2>
            <p class="text-muted">Kelola data donor dan kontributor</p>
        </div>
        <button class="btn btn-dark px-4 py-2" id="addDonorBtn">
            <i class="bi bi-plus-lg me-2"></i> Tambah Donor
        </button>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card shadow-sm p-4 border-0 text-center">
                <small class="text-muted d-block mb-1">Total Donor</small>
                <h2 class="fw-bold mb-0"><?php echo $userModel->countDonors(); ?></h2>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm p-4 border-0 text-center text-success">
                <small class="text-muted d-block mb-1">Total Donasi</small>
                <h2 class="fw-bold mb-0"><?php echo $totalDonations; ?></h2>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm p-4 border-0 text-center text-primary">
                <small class="text-muted d-block mb-1">Total Kontribusi</small>
                <h2 class="fw-bold mb-0"><?php echo formatRupiah($totalAmount); ?></h2>
            </div>
        </div>
    </div>

    <!-- Search and Filter -->
    <div class="row mb-4">
        <div class="col-md-8">
            <form method="GET" action="" class="input-group shadow-sm rounded">
                <span class="input-group-text bg-white border-0 ps-3">
                    <i class="bi bi-search text-muted"></i>
                </span>
                <input type="text" name="search" class="form-control border-0 py-2" 
                       placeholder="Cari donor berdasarkan nama atau email..." 
                       value="<?php echo htmlspecialchars($search); ?>">
                <?php if ($search || $filter !== 'all'): ?>
                    <a href="donor.php" class="btn btn-outline-secondary">Clear</a>
                <?php endif; ?>
            </form>
        </div>
        <div class="col-md-4">
            <form method="GET" action="" class="input-group">
                <select name="filter" class="form-select" onchange="this.form.submit()">
                    <option value="all" <?php echo $filter === 'all' ? 'selected' : ''; ?>>Semua Donor</option>
                    <option value="has_donations" <?php echo $filter === 'has_donations' ? 'selected' : ''; ?>>Sudah Donasi</option>
                    <option value="no_donations" <?php echo $filter === 'no_donations' ? 'selected' : ''; ?>>Belum Donasi</option>
                </select>
                <input type="hidden" name="search" value="<?php echo htmlspecialchars($search); ?>">
            </form>
        </div>
    </div>

    <!-- Donors Table -->
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="p-3 border-bottom">
                <h6 class="fw-bold mb-1">Daftar Donor</h6>
                <small class="text-muted">Menampilkan <?php echo count($donors); ?> donor</small>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-muted small">
                        <tr>
                            <th class="ps-3 border-0">Donor</th>
                            <th class="border-0">Kontak</th>
                            <th class="border-0">Alamat</th>
                            <th class="border-0">Bergabung</th>
                            <th class="border-0">Statistik</th>
                            <th class="border-0 text-end pe-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="small">
                        <?php if (empty($donors)): ?>
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <i class="bi bi-people display-6 text-muted mb-2 d-block"></i>
                                    <p class="text-muted mb-0">Tidak ada donor ditemukan</p>
                                    <?php if ($search || $filter !== 'all'): ?>
                                        <a href="donor.php" class="btn btn-sm btn-outline-primary mt-2">Tampilkan Semua</a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($donors as $donor): 
                                $initials = getInitials($donor['nama_pengguna']);
                                $avatarColor = getAvatarColor($donor['nama_pengguna']);
                                $joinDate = date('d M Y', strtotime($donor['dibuat_pada']));
                            ?>
                            <tr>
                                <td class="ps-3">
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle <?php echo $avatarColor; ?> text-white d-flex align-items-center justify-content-center me-3" 
                                             style="width: 40px; height: 40px;">
                                            <?php echo $initials; ?>
                                        </div>
                                        <div>
                                            <div class="fw-bold"><?php echo htmlspecialchars($donor['nama_pengguna']); ?></div>
                                            <div class="text-muted" style="font-size: 11px;">ID: <?php echo $donor['id_pengguna']; ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="text-muted mb-1">
                                        <i class="bi bi-envelope me-1"></i> 
                                        <?php echo htmlspecialchars($donor['email']); ?>
                                    </div>
                                    <?php if ($donor['no_hp']): ?>
                                    <div class="text-muted">
                                        <i class="bi bi-telephone me-1"></i> 
                                        <?php echo htmlspecialchars($donor['no_hp']); ?>
                                    </div>
                                    <?php endif; ?>
                                </td>
                                <td class="text-muted" style="max-width: 200px;">
                                    <?php echo htmlspecialchars($donor['alamat'] ?: '-'); ?>
                                </td>
                                <td>
                                    <?php echo $joinDate; ?>
                                </td>
                                <td>
                                    <?php if ($donor['donation_count'] > 0): ?>
                                        <div class="fw-bold text-primary">
                                            <?php echo formatRupiah($donor['donation_total']); ?>
                                        </div>
                                        <div class="text-muted small">
                                            <?php echo $donor['donation_count']; ?> donasi
                                        </div>
                                    <?php else: ?>
                                        <span class="text-muted small">Belum ada donasi</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end pe-3">
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-sm btn-light border edit-donor-btn"
                                                data-id="<?php echo $donor['id_pengguna']; ?>"
                                                title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <?php if ($donor['donation_count'] == 0): ?>
                                        <button type="button" class="btn btn-sm btn-light border text-danger delete-donor-btn ms-1"
                                                data-id="<?php echo $donor['id_pengguna']; ?>"
                                                data-name="<?php echo htmlspecialchars($donor['nama_pengguna']); ?>"
                                                title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                        <?php else: ?>
                                        <button type="button" class="btn btn-sm btn-light border text-muted ms-1"
                                                title="Tidak dapat dihapus karena memiliki riwayat donasi"
                                                disabled>
                                            <i class="bi bi-trash"></i>
                                        </button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <?php if (count($donors) > 10): ?>
                <div class="p-3 border-top">
                    <nav aria-label="Page navigation">
                        <ul class="pagination justify-content-center mb-0">
                            <li class="page-item disabled">
                                <a class="page-link" href="#" tabindex="-1">Previous</a>
                            </li>
                            <li class="page-item active"><a class="page-link" href="#">1</a></li>
                            <li class="page-item"><a class="page-link" href="#">2</a></li>
                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                            <li class="page-item">
                                <a class="page-link" href="#">Next</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Top Donors Card -->
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h6 class="fw-bold mb-3">Top 5 Donor</h6>
                    <?php
                    // Get top 5 donors by donation amount
                    $conn = (new Database())->getConnection();
                    $sql = "SELECT u.id_pengguna, u.nama_pengguna, u.email,
                            COUNT(d.id_donasi) as donation_count,
                            SUM(d.jumlah_donasi) as donation_total
                            FROM users u
                            LEFT JOIN donasi d ON u.id_pengguna = d.id_pengguna 
                            AND d.status = 'berhasil'
                            WHERE u.role = 'donatur'
                            GROUP BY u.id_pengguna
                            ORDER BY donation_total DESC
                            LIMIT 5";
                    $result = $conn->query($sql);
                    
                    if ($result->num_rows > 0):
                        $rank = 1;
                        while ($row = $result->fetch_assoc()):
                            $donationTotal = $row['donation_total'] ?? 0;
                            if ($donationTotal > 0):
                    ?>
                    <div class="d-flex align-items-center mb-3">
                        <div class="rounded-circle bg-light d-flex align-items-center justify-content-center me-3" 
                             style="width: 40px; height: 40px;">
                            <span class="fw-bold text-primary">#<?php echo $rank; ?></span>
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between">
                                <strong class="small"><?php echo htmlspecialchars($row['nama_pengguna']); ?></strong>
                                <span class="text-primary fw-bold small"><?php echo formatRupiah($donationTotal); ?></span>
                            </div>
                            <small class="text-muted">
                                <?php echo $row['donation_count']; ?> donasi
                            </small>
                        </div>
                    </div>
                    <?php 
                            $rank++;
                            endif;
                        endwhile;
                    else:
                    ?>
                    <div class="text-center py-3">
                        <i class="bi bi-currency-exchange display-6 text-muted mb-2"></i>
                        <p class="text-muted mb-0">Belum ada data donor</p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h6 class="fw-bold mb-3">Donor Baru (30 Hari Terakhir)</h6>
                    <?php
                    // Get new donors in last 30 days
                    $sql = "SELECT u.*, 
                            (SELECT COUNT(*) FROM donasi d WHERE d.id_pengguna = u.id_pengguna AND d.status = 'berhasil') as donation_count
                            FROM users u
                            WHERE u.role = 'donatur'
                            AND u.dibuat_pada >= DATE_SUB(NOW(), INTERVAL 30 DAY)
                            ORDER BY u.dibuat_pada DESC
                            LIMIT 5";
                    $result = $conn->query($sql);
                    
                    if ($result->num_rows > 0):
                        while ($row = $result->fetch_assoc()):
                            $daysAgo = floor((time() - strtotime($row['dibuat_pada'])) / (60 * 60 * 24));
                    ?>
                    <div class="d-flex align-items-center mb-3">
                        <div class="rounded-circle bg-light d-flex align-items-center justify-content-center me-3" 
                             style="width: 40px; height: 40px;">
                            <i class="bi bi-person-plus text-success"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between">
                                <strong class="small"><?php echo htmlspecialchars($row['nama_pengguna']); ?></strong>
                                <span class="badge bg-success bg-opacity-10 text-success small">
                                    <?php echo $daysAgo; ?> hari lalu
                                </span>
                            </div>
                            <small class="text-muted">
                                <?php echo $row['donation_count'] > 0 ? 
                                    $row['donation_count'] . ' donasi' : 'Belum donasi'; ?>
                            </small>
                        </div>
                    </div>
                    <?php 
                        endwhile;
                    else:
                    ?>
                    <div class="text-center py-3">
                        <i class="bi bi-person-plus display-6 text-muted mb-2"></i>
                        <p class="text-muted mb-0">Tidak ada donor baru</p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- Modal Add Donor -->
<div class="modal fade" id="addDonorModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Donor Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="addDonorForm">
                <div class="modal-body">
                    <div id="addDonorAlert" class="alert d-none"></div>
                    
                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" class="form-control" name="nama_pengguna" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" class="form-control" name="password" required>
                        <small class="text-muted">Minimal 6 karakter</small>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Nomor HP</label>
                        <input type="tel" class="form-control" name="no_hp">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Alamat</label>
                        <textarea class="form-control" name="alamat" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-dark">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Donor -->
<div class="modal fade" id="editDonorModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Donor</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editDonorForm">
                <input type="hidden" name="id_pengguna" id="edit_id_pengguna">
                <div class="modal-body">
                    <div id="editDonorAlert" class="alert d-none"></div>
                    
                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" class="form-control" name="nama_pengguna" id="edit_nama_pengguna" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" id="edit_email" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Password (Kosongkan jika tidak ingin mengubah)</label>
                        <input type="password" class="form-control" name="password">
                        <small class="text-muted">Biarkan kosong jika tidak ingin mengubah password</small>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Nomor HP</label>
                        <input type="tel" class="form-control" name="no_hp" id="edit_no_hp">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Alamat</label>
                        <textarea class="form-control" name="alamat" id="edit_alamat" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-dark">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteDonorModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus donor <strong id="deleteDonorName"></strong>?</p>
                <div class="alert alert-warning small">
                    <i class="bi bi-exclamation-triangle me-1"></i>
                    Aksi ini tidak dapat dibatalkan. Semua data donor akan dihapus.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <a href="#" class="btn btn-danger" id="deleteDonorConfirmBtn">Hapus</a>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
$(document).ready(function() {
    console.log('Donor page loaded');
    
    // Add Donor Modal
    $('#addDonorBtn').click(function() {
        $('#addDonorModal').modal('show');
    });
    
    // Edit Donor Modal
    $(document).on('click', '.edit-donor-btn', function() {
        const donorId = $(this).data('id');
        
        // Show loading
        $('#editDonorAlert').removeClass('d-none alert-danger alert-success')
                           .addClass('alert-info')
                           .html('Memuat data donor...');
        
        // Fetch donor data
        $.ajax({
            url: 'donor-process.php',
            type: 'GET',
            data: { get_donor: 1, id: donorId },
            success: function(response) {
                try {
                    const data = JSON.parse(response);
                    if (data.success) {
                        const donor = data.donor;
                        
                        // Populate form
                        $('#edit_id_pengguna').val(donor.id_pengguna);
                        $('#edit_nama_pengguna').val(donor.nama_pengguna);
                        $('#edit_email').val(donor.email);
                        $('#edit_no_hp').val(donor.no_hp || '');
                        $('#edit_alamat').val(donor.alamat || '');
                        
                        $('#editDonorAlert').addClass('d-none');
                        $('#editDonorModal').modal('show');
                    } else {
                        $('#editDonorAlert').removeClass('d-none alert-info').addClass('alert-danger')
                                          .html(data.message || 'Gagal memuat data');
                    }
                } catch (e) {
                    $('#editDonorAlert').removeClass('d-none alert-info').addClass('alert-danger')
                                      .html('Error parsing response');
                }
            },
            error: function() {
                $('#editDonorAlert').removeClass('d-none alert-info').addClass('alert-danger')
                                  .html('Gagal memuat data donor');
            }
        });
    });
    
    // Delete Donor Confirmation
    $(document).on('click', '.delete-donor-btn', function() {
        const donorId = $(this).data('id');
        const donorName = $(this).data('name');
        
        $('#deleteDonorName').text(donorName);
        $('#deleteDonorConfirmBtn').attr('href', 'donor.php?delete=' + donorId);
        $('#deleteDonorModal').modal('show');
    });
    
    // Add Donor Form Submission
    $('#addDonorForm').submit(function(e) {
        e.preventDefault();
        
        // Basic validation
        const password = $('input[name="password"]').val();
        if (password.length < 6) {
            $('#addDonorAlert').removeClass('d-none alert-success').addClass('alert-danger')
                             .html('Password minimal 6 karakter');
            return;
        }
        
        // Prepare form data
        const formData = $(this).serializeArray();
        
        // Show loading
        const submitBtn = $(this).find('button[type="submit"]');
        const originalText = submitBtn.html();
        submitBtn.html('<span class="spinner-border spinner-border-sm"></span> Menyimpan...');
        submitBtn.prop('disabled', true);
        
        // Submit via AJAX
        $.ajax({
            url: 'donor-process.php?action=add',
            type: 'POST',
            data: $.param(formData),
            success: function(response) {
                try {
                    const data = JSON.parse(response);
                    if (data.success) {
                        $('#addDonorAlert').removeClass('d-none alert-danger').addClass('alert-success')
                                         .html(data.message || 'Donor berhasil ditambahkan');
                        
                        // Clear form
                        $('#addDonorForm')[0].reset();
                        
                        // Reload page after 2 seconds
                        setTimeout(() => {
                            window.location.reload();
                        }, 2000);
                    } else {
                        $('#addDonorAlert').removeClass('d-none').addClass('alert-danger')
                                         .html(data.message || 'Gagal menambahkan donor');
                        submitBtn.html(originalText);
                        submitBtn.prop('disabled', false);
                    }
                } catch (e) {
                    $('#addDonorAlert').removeClass('d-none').addClass('alert-danger')
                                     .html('Error processing response');
                    submitBtn.html(originalText);
                    submitBtn.prop('disabled', false);
                }
            },
            error: function() {
                $('#addDonorAlert').removeClass('d-none').addClass('alert-danger')
                                 .html('Terjadi kesalahan saat menyimpan');
                submitBtn.html(originalText);
                submitBtn.prop('disabled', false);
            }
        });
    });
    
    // Edit Donor Form Submission
    $('#editDonorForm').submit(function(e) {
        e.preventDefault();
        
        // Prepare form data
        const formData = $(this).serializeArray();
        
        // Show loading
        const submitBtn = $(this).find('button[type="submit"]');
        const originalText = submitBtn.html();
        submitBtn.html('<span class="spinner-border spinner-border-sm"></span> Menyimpan...');
        submitBtn.prop('disabled', true);
        
        // Submit via AJAX
        $.ajax({
            url: 'donor-process.php?action=edit',
            type: 'POST',
            data: $.param(formData),
            success: function(response) {
                try {
                    const data = JSON.parse(response);
                    if (data.success) {
                        $('#editDonorAlert').removeClass('d-none alert-danger').addClass('alert-success')
                                          .html(data.message || 'Donor berhasil diupdate');
                        
                        // Close modal and reload after 2 seconds
                        setTimeout(() => {
                            $('#editDonorModal').modal('hide');
                            window.location.reload();
                        }, 2000);
                    } else {
                        $('#editDonorAlert').removeClass('d-none').addClass('alert-danger')
                                          .html(data.message || 'Gagal mengupdate donor');
                        submitBtn.html(originalText);
                        submitBtn.prop('disabled', false);
                    }
                } catch (e) {
                    $('#editDonorAlert').removeClass('d-none').addClass('alert-danger')
                                      .html('Error processing response');
                    submitBtn.html(originalText);
                    submitBtn.prop('disabled', false);
                }
            },
            error: function() {
                $('#editDonorAlert').removeClass('d-none').addClass('alert-danger')
                                  .html('Terjadi kesalahan saat menyimpan');
                submitBtn.html(originalText);
                submitBtn.prop('disabled', false);
            }
        });
    });
    
    // Auto-hide alerts
    setTimeout(() => {
        $('.alert').alert('close');
    }, 5000);
    
    // Focus search input
    $('input[name="search"]').focus();
});
</script>

</body>
</html>