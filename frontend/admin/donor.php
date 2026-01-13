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
    $donors = array_filter($donors, function ($donor) use ($search) {
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

function formatRupiah($angka)
{
    return 'Rp ' . number_format($angka, 0, ',', '.');
}

function getInitials($name)
{
    $words = explode(' ', $name);
    $initials = '';
    foreach ($words as $word) {
        $initials .= strtoupper(substr($word, 0, 1));
    }
    return substr($initials, 0, 2);
}

function getAvatarColor($name)
{
    $colors = ['bg-primary', 'bg-success', 'bg-danger', 'bg-warning', 'bg-info', 'bg-secondary', 'bg-dark'];
    return $colors[crc32($name) % count($colors)];
}
?>

<?php include 'header.php'; ?>

<!-- FIXED MAIN LAYOUT -->
<main class="col-md-12 col-lg-12 px-md-4 py-4">

    <div class="container-xl">

        <!-- Alert -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <?= $_SESSION['success'];
                unset($_SESSION['success']); ?>
                <button class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <?= $_SESSION['error'];
                unset($_SESSION['error']); ?>
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

<!-- Modal for Add/Edit Donor -->
<div class="modal fade" id="donorModal" tabindex="-1" aria-labelledby="donorModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="donorModalLabel">Tambah Donor</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div id="modalAlert" class="alert d-none mx-3 mt-3" role="alert"></div>
            <form id="donorForm">
                <div class="modal-body">
                    <input type="hidden" name="id_pengguna" id="modalId">
                    
                    <div class="mb-3">
                        <label for="nama_pengguna" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nama_pengguna" name="nama_pengguna" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" id="password" name="password" required>
                        <div class="form-text">Minimal 6 karakter</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="no_hp" class="form-label">No. HP</label>
                        <input type="text" class="form-control" id="no_hp" name="no_hp">
                    </div>
                    
                    <div class="mb-3">
                        <label for="alamat" class="form-label">Alamat</label>
                        <textarea class="form-control" id="alamat" name="alamat" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" id="submitBtn">
                        <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
$(document).ready(function() {
    console.log('Document ready - initializing donor modals');
    
    // Initialize modal
    const donorModalEl = document.getElementById('donorModal');
    const donorModal = new bootstrap.Modal(donorModalEl);
    
    console.log('Donor modal initialized:', !!donorModalEl);

    // Add Donor Button
    $('#addDonorBtn').click(function(e) {
        e.preventDefault();
        console.log('Add donor button clicked');
        
        // Reset form
        document.getElementById('donorForm').reset();
        document.getElementById('modalId').value = '';
        document.getElementById('donorModalLabel').textContent = 'Tambah Donor';
        document.getElementById('password').required = true;
        document.getElementById('modalAlert').classList.add('d-none');
        
        donorModal.show();
    });

    // Edit Donor Button (using event delegation)
    $(document).on('click', '.edit-donor-btn', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        const id = $(this).data('id');
        console.log('Edit donor button clicked for ID:', id);
        
        // Fetch donor data
        $.ajax({
            url: 'donor-process.php?get_donor=1&id=' + id,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    const donor = response.donor;
                    
                    // Fill form
                    document.getElementById('modalId').value = donor.id_pengguna;
                    document.getElementById('nama_pengguna').value = donor.nama_pengguna;
                    document.getElementById('email').value = donor.email;
                    document.getElementById('password').value = '';
                    document.getElementById('password').required = false;
                    document.getElementById('no_hp').value = donor.no_hp || '';
                    document.getElementById('alamat').value = donor.alamat || '';
                    document.getElementById('donorModalLabel').textContent = 'Edit Donor';
                    document.getElementById('modalAlert').classList.add('d-none');
                    
                    donorModal.show();
                } else {
                    alert('Gagal memuat data donor: ' + response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', xhr, status, error);
                alert('Terjadi kesalahan saat memuat data donor');
            }
        });
    });

    // Form submission with AJAX
    $('#donorForm').submit(function(e) {
        e.preventDefault();
        console.log('Donor form submitted');
        
        const formData = new FormData(this);
        const isEdit = formData.get('id_pengguna') !== '';
        const action = isEdit ? 'edit' : 'add';
        
        console.log('Action:', action, 'Is Edit:', isEdit);
        
        // Show loading
        const submitBtn = $('#submitBtn');
        const spinner = submitBtn.find('.spinner-border');
        const originalText = submitBtn.html();
        spinner.removeClass('d-none');
        submitBtn.prop('disabled', true);
        
        // Submit via AJAX
        $.ajax({
            url: 'donor-process.php?action=' + action,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(response) {
                console.log('AJAX Response:', response);
                
                const alertDiv = $('#modalAlert');
                
                if (response.success) {
                    alertDiv.removeClass('alert-danger').addClass('alert-success')
                           .html('<i class="bi bi-check-circle me-2"></i>' + response.message)
                           .removeClass('d-none');
                    
                    // Reset form if adding new
                    if (!isEdit) {
                        $('#donorForm')[0].reset();
                    }
                    
                    // Reload page after 2 seconds
                    setTimeout(() => {
                        window.location.reload();
                    }, 2000);
                } else {
                    alertDiv.removeClass('alert-success').addClass('alert-danger')
                           .html('<i class="bi bi-exclamation-triangle me-2"></i>' + response.message)
                           .removeClass('d-none');
                    
                    // Reset button
                    spinner.addClass('d-none');
                    submitBtn.prop('disabled', false);
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', xhr, status, error);
                const alertDiv = $('#modalAlert');
                alertDiv.removeClass('alert-success').addClass('alert-danger')
                       .html('<i class="bi bi-exclamation-triangle me-2"></i>Terjadi kesalahan saat menyimpan')
                       .removeClass('d-none');
                
                // Reset button
                spinner.addClass('d-none');
                submitBtn.prop('disabled', false);
            }
        });
    });
    
    console.log('All donor event handlers attached');
});
</script>

<?php include 'footer.php'; ?>