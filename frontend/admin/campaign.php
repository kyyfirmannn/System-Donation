<?php
// frontend/admin/campaign.php
require_once __DIR__ . '/../../backend/config/database.php';
require_once __DIR__ . '/../../backend/models/CampaignModel.php';
require_once __DIR__ . '/../../backend/models/OrganizationModel.php';
require_once __DIR__ . '/../../backend/models/DonationModel.php';

$campaignModel = new CampaignModel();
$orgModel = new OrganizationModel();
$donationModel = new DonationModel();

// Handle delete campaign
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $conn = (new Database())->getConnection();
    $sql = "DELETE FROM kampanye WHERE id_kampanye = $id";
    if ($conn->query($sql)) {
        $_SESSION['success'] = "Campaign berhasil dihapus";
    } else {
        $_SESSION['error'] = "Gagal menghapus campaign";
    }
    header('Location: campaign.php');
    exit;
}

// Ambil semua campaign
$campaigns = $campaignModel->getAllCampaigns();

// Hitung statistik
$totalCampaigns = $campaignModel->countCampaigns();
$activeCampaigns = $campaignModel->countCampaigns('aktif');
$totalTerkumpul = 0;
foreach ($campaigns as $campaign) {
    $totalTerkumpul += $campaign['dana_terkumpul'];
}

// Handle search
$search = $_GET['search'] ?? '';
if ($search) {
    $campaigns = array_filter($campaigns, function($campaign) use ($search) {
        return stripos($campaign['judul_kampanye'], $search) !== false ||
               stripos($campaign['deskripsi'], $search) !== false;
    });
}

// Get all organizations
$organizations = $orgModel->getAllOrganizations();

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

function getStatusBadge($status) {
    switch ($status) {
        case 'aktif': return 'bg-dark';
        case 'selesai': return 'bg-success';
        case 'dibatalkan': return 'bg-danger';
        default: return 'bg-secondary';
    }
}

function getStatusText($status) {
    switch ($status) {
        case 'aktif': return 'Aktif';
        case 'selesai': return 'Selesai';
        case 'dibatalkan': return 'Dibatalkan';
        default: return $status;
    }
}
?>
<?php include 'header.php'; ?>

                <!-- Main Content -->
                <div class="px-md-4 py-4">
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
                            <h2 class="fw-bold mb-0">Manajemen Campaign</h2>
                            <p class="text-muted">Kelola semua campaign donasi</p>
                        </div>
                        <button class="btn btn-dark px-4 py-2" id="addCampaignBtn">
                            <i class="bi bi-plus-lg me-2"></i> Tambah Campaign
                        </button>
                    </div>

                    <!-- Statistics Cards -->
                    <div class="row g-3 mb-4 text-center">
                        <div class="col-md-4">
                            <div class="card shadow-sm p-4">
                                <small class="text-muted d-block mb-1">Total Campaign</small>
                                <h2 class="fw-bold mb-0"><?php echo $totalCampaigns; ?></h2>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card shadow-sm p-4">
                                <small class="text-muted d-block mb-1 text-success">Campaign Aktif</small>
                                <h2 class="fw-bold mb-0 text-success"><?php echo $activeCampaigns; ?></h2>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card shadow-sm p-4">
                                <small class="text-muted d-block mb-1 text-primary">Total Terkumpul</small>
                                <h2 class="fw-bold mb-0 text-primary"><?php echo formatRupiah($totalTerkumpul); ?></h2>
                            </div>
                        </div>
                    </div>

                    <!-- Search -->
                    <form method="GET" action="" class="mb-4">
                        <div class="input-group shadow-sm rounded">
                            <span class="input-group-text bg-white border-0"><i class="bi bi-search text-muted"></i></span>
                            <input type="text" name="search" class="form-control border-0 py-2" 
                                   placeholder="Cari campaign..." value="<?php echo htmlspecialchars($search); ?>">
                            <?php if ($search): ?>
                                <a href="campaign.php" class="btn btn-outline-secondary">Clear</a>
                            <?php endif; ?>
                        </div>
                    </form>

                    <!-- Campaign Table -->
                    <div class="card shadow-sm">
                        <div class="card-body p-0">
                            <div class="p-3 border-bottom">
                                <h6 class="fw-bold mb-1">Daftar Campaign</h6>
                                <small class="text-muted">Menampilkan <?php echo count($campaigns); ?> campaign</small>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="bg-light text-muted">
                                        <tr>
                                            <th class="ps-3 border-0">Campaign</th>
                                            <th class="border-0 text-center">Organisasi</th>
                                            <th class="border-0">Progress</th>
                                            <th class="border-0">Target</th>
                                            <th class="border-0 text-center">Status</th>
                                            <th class="border-0 text-center">Sisa Hari</th>
                                            <th class="border-0 text-end pe-3">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (empty($campaigns)): ?>
                                            <tr>
                                                <td colspan="7" class="text-center py-4">
                                                    <i class="bi bi-inbox display-6 text-muted mb-2 d-block"></i>
                                                    <p class="text-muted mb-0">Tidak ada campaign ditemukan</p>
                                                    <?php if ($search): ?>
                                                        <a href="campaign.php" class="btn btn-sm btn-outline-primary mt-2">Tampilkan Semua</a>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php else: ?>
                                            <?php foreach ($campaigns as $campaign): 
                                                $progress = 0;
                                                if ($campaign['target_dana'] > 0) {
                                                    $progress = ($campaign['dana_terkumpul'] / $campaign['target_dana']) * 100;
                                                }
                                                $progress = min(100, $progress);
                                                
                                                $sisaHari = 0;
                                                if ($campaign['tgl_selesai']) {
                                                    $today = new DateTime();
                                                    $endDate = new DateTime($campaign['tgl_selesai']);
                                                    $sisaHari = $today->diff($endDate)->days;
                                                    if ($today > $endDate) $sisaHari = 0;
                                                }
                                                
                                                $organization = $orgModel->getOrganizationById($campaign['id_organisasi']);
                                                $orgName = $organization ? $organization['nama_organisasi'] : 'Tidak diketahui';
                                                
                                                $progressColor = 'bg-dark';
                                                if ($progress >= 100) $progressColor = 'bg-success';
                                                elseif ($progress >= 70) $progressColor = 'bg-primary';
                                                elseif ($progress >= 30) $progressColor = 'bg-warning';
                                            ?>
                                            <tr>
                                                <td class="ps-3">
                                                    <div class="fw-bold mb-1"><?php echo htmlspecialchars($campaign['judul_kampanye']); ?></div>
                                                    <small class="text-muted">
                                                        <?php 
                                                        $deskripsi = strip_tags($campaign['deskripsi']);
                                                        echo strlen($deskripsi) > 50 ? substr($deskripsi, 0, 50) . '...' : $deskripsi;
                                                        ?>
                                                    </small>
                                                    <div class="mt-1">
                                                        <small class="text-muted">
                                                            <i class="bi bi-calendar-event me-1"></i>
                                                            <?php echo date('d M Y', strtotime($campaign['tgl_mulai'])); ?> - 
                                                            <?php echo date('d M Y', strtotime($campaign['tgl_selesai'])); ?>
                                                        </small>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge rounded-pill border text-dark fw-normal px-3 py-2">
                                                        <?php echo htmlspecialchars($orgName); ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <small class="d-flex justify-content-between">
                                                        <span><?php echo number_format($progress, 1); ?>%</span>
                                                        <span class="text-muted small"><?php echo formatRupiah($campaign['dana_terkumpul']); ?></span>
                                                    </small>
                                                    <div class="progress mt-1" style="height: 6px;">
                                                        <div class="progress-bar <?php echo $progressColor; ?>" style="width: <?php echo $progress; ?>%"></div>
                                                    </div>
                                                </td>
                                                <td class="fw-bold small"><?php echo formatRupiah($campaign['target_dana']); ?></td>
                                                <td class="text-center">
                                                    <span class="badge <?php echo getStatusBadge($campaign['status']); ?> px-3 py-2">
                                                        <?php echo getStatusText($campaign['status']); ?>
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    <?php if ($campaign['status'] == 'aktif' && $sisaHari > 0): ?>
                                                        <span class="badge bg-light text-dark px-3 py-2">
                                                            <?php echo $sisaHari; ?> hari
                                                        </span>
                                                    <?php elseif ($campaign['status'] == 'aktif'): ?>
                                                        <span class="badge bg-warning text-dark px-3 py-2">
                                                            Berakhir
                                                        </span>
                                                    <?php else: ?>
                                                        <span class="text-muted small">-</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-end pe-3">
                                                    <div class="btn-group" role="group">
                                                        <a href="../user/detail.php?id=<?php echo $campaign['id_kampanye']; ?>" 
                                                           class="btn btn-sm btn-light border" target="_blank" title="Lihat">
                                                            <i class="bi bi-eye"></i>
                                                        </a>
                                                        <button type="button" class="btn btn-sm btn-light border mx-1 edit-campaign-btn"
                                                                data-id="<?php echo $campaign['id_kampanye']; ?>" title="Edit">
                                                            <i class="bi bi-pencil"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-light border text-danger delete-campaign-btn"
                                                                data-id="<?php echo $campaign['id_kampanye']; ?>" 
                                                                data-title="<?php echo htmlspecialchars($campaign['judul_kampanye']); ?>" title="Hapus">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Modal Add Campaign -->
    <div class="modal fade" id="addCampaignModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Campaign Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="addCampaignForm">
                    <div class="modal-body">
                        <div id="addAlert" class="alert d-none"></div>
                        
                        <div class="mb-3">
                            <label class="form-label">Judul Campaign</label>
                            <input type="text" class="form-control" name="judul_kampanye" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Organisasi</label>
                            <select class="form-select" name="id_organisasi" required>
                                <option value="">Pilih Organisasi</option>
                                <?php foreach ($organizations as $org): ?>
                                <option value="<?php echo $org['id_organisasi']; ?>">
                                    <?php echo htmlspecialchars($org['nama_organisasi']); ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Target Dana (Rp)</label>
                                <input type="text" class="form-control currency" name="target_dana" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Dana Terkumpul Awal (Rp)</label>
                                <input type="text" class="form-control currency" name="dana_terkumpul" value="0">
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tanggal Mulai</label>
                                <input type="date" class="form-control" name="tgl_mulai" 
                                       value="<?php echo date('Y-m-d'); ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tanggal Selesai</label>
                                <input type="date" class="form-control" name="tgl_selesai" required>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select class="form-select" name="status">
                                <option value="aktif" selected>Aktif</option>
                                <option value="selesai">Selesai</option>
                                <option value="dibatalkan">Dibatalkan</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Deskripsi</label>
                            <textarea class="form-control" name="deskripsi" rows="4" required></textarea>
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

    <!-- Modal Edit Campaign -->
    <div class="modal fade" id="editCampaignModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Campaign</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="editCampaignForm">
                    <input type="hidden" name="id_kampanye" id="edit_id_kampanye">
                    <div class="modal-body">
                        <div id="editAlert" class="alert d-none"></div>
                        
                        <div class="mb-3">
                            <label class="form-label">Judul Campaign</label>
                            <input type="text" class="form-control" name="judul_kampanye" id="edit_judul" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Organisasi</label>
                            <select class="form-select" name="id_organisasi" id="edit_organisasi" required>
                                <option value="">Pilih Organisasi</option>
                                <?php foreach ($organizations as $org): ?>
                                <option value="<?php echo $org['id_organisasi']; ?>">
                                    <?php echo htmlspecialchars($org['nama_organisasi']); ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Target Dana (Rp)</label>
                                <input type="text" class="form-control currency" name="target_dana" id="edit_target_dana" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Dana Terkumpul (Rp)</label>
                                <input type="text" class="form-control currency" name="dana_terkumpul" id="edit_dana_terkumpul" required>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tanggal Mulai</label>
                                <input type="date" class="form-control" name="tgl_mulai" id="edit_tgl_mulai" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tanggal Selesai</label>
                                <input type="date" class="form-control" name="tgl_selesai" id="edit_tgl_selesai" required>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select class="form-select" name="status" id="edit_status">
                                <option value="aktif">Aktif</option>
                                <option value="selesai">Selesai</option>
                                <option value="dibatalkan">Dibatalkan</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Deskripsi</label>
                            <textarea class="form-control" name="deskripsi" id="edit_deskripsi" rows="4" required></textarea>
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
    <div class="modal fade" id="deleteCampaignModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus campaign <strong id="deleteCampaignTitle"></strong>?</p>
                    <div class="alert alert-warning small">
                        <i class="bi bi-exclamation-triangle me-1"></i>
                        Aksi ini tidak dapat dibatalkan.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <a href="#" class="btn btn-danger" id="deleteConfirmBtn">Hapus</a>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
$(document).ready(function() {
    console.log('Page loaded successfully - Debug Mode');
    
    // 1. TEST: Cek apakah jQuery bekerja
    console.log('jQuery version:', $.fn.jquery);
    
    // 2. TEST: Cek apakah modal HTML ada di DOM
    console.log('Add Modal exists:', $('#addCampaignModal').length > 0);
    console.log('Edit Modal exists:', $('#editCampaignModal').length > 0);
    console.log('Delete Modal exists:', $('#deleteCampaignModal').length > 0);
    
    // 3. TEST: Hitung tombol edit
    const editButtons = $('.edit-campaign-btn');
    console.log('Edit buttons found:', editButtons.length);
    
    // Format currency inputs
    function formatCurrencyInput(input) {
        input.on('input', function() {
            let value = $(this).val().replace(/\D/g, '');
            if (value) {
                value = parseInt(value).toLocaleString('id-ID');
                $(this).val(value);
            }
        });
        
        input.on('focus', function() {
            $(this).val($(this).val().replace(/\D/g, ''));
        });
        
        input.on('blur', function() {
            let value = $(this).val().replace(/\D/g, '');
            if (value) {
                value = parseInt(value).toLocaleString('id-ID');
                $(this).val(value);
            }
        });
    }
    
    // Initialize currency formatting
    $('.currency').each(function() {
        formatCurrencyInput($(this));
    });
    
    // 4. Tombol Add Modal (Bootstrap 5)
    const addCampaignModalEl = document.getElementById('addCampaignModal');
    const addCampaignModal = new bootstrap.Modal(addCampaignModalEl);
    const editCampaignModalEl = document.getElementById('editCampaignModal');
    const editCampaignModal = new bootstrap.Modal(editCampaignModalEl);
    const deleteCampaignModalEl = document.getElementById('deleteCampaignModal');
    const deleteCampaignModal = new bootstrap.Modal(deleteCampaignModalEl);

    $('#addCampaignBtn').click(function(e) {
        e.preventDefault();
        console.log('Add button clicked - Opening modal');
        addCampaignModal.show();
    });
    
    // 5. PERBAIKAN: Tombol Edit Modal - Gunakan event delegation
    $(document).on('click', '.edit-campaign-btn', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        const campaignId = $(this).data('id');
        console.log('Edit button clicked for campaign ID:', campaignId);
        console.log('Button element:', this);
        
        // Show modal DULU agar user tidak menunggu
        editCampaignModal.show();
        
        // Show loading message
        $('#editAlert').removeClass('d-none alert-danger alert-success')
                      .addClass('alert-info')
                      .html('<i class="bi bi-hourglass-split me-2"></i>Memuat data campaign...');
        
        // Fetch campaign data via AJAX
        $.ajax({
            url: 'campaign-process.php',
            type: 'GET',
            data: { 
                get_campaign: 1, 
                id: campaignId 
            },
            dataType: 'json', // Expect JSON response
            timeout: 10000, // 10 second timeout
            success: function(response) {
                console.log('AJAX Success Response:', response);
                
                if (response.success && response.campaign) {
                    const campaign = response.campaign;
                    
                    // Populate form fields
                    $('#edit_id_kampanye').val(campaign.id_kampanye);
                    $('#edit_judul').val(campaign.judul_kampanye);
                    $('#edit_organisasi').val(campaign.id_organisasi);
                    $('#edit_target_dana').val(parseInt(campaign.target_dana).toLocaleString('id-ID'));
                    $('#edit_dana_terkumpul').val(parseInt(campaign.dana_terkumpul).toLocaleString('id-ID'));
                    $('#edit_tgl_mulai').val(campaign.tgl_mulai);
                    $('#edit_tgl_selesai').val(campaign.tgl_selesai);
                    $('#edit_status').val(campaign.status);
                    $('#edit_deskripsi').val(campaign.deskripsi);
                    
                    // Hide loading message
                    $('#editAlert').addClass('d-none');
                    
                    console.log('Form populated successfully');
                } else {
                    $('#editAlert').removeClass('d-none alert-info').addClass('alert-danger')
                                  .html('<i class="bi bi-exclamation-triangle me-2"></i>' + 
                                        (response.message || 'Gagal memuat data campaign'));
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', status, error);
                $('#editAlert').removeClass('d-none alert-info').addClass('alert-danger')
                              .html('<i class="bi bi-exclamation-triangle me-2"></i>Error: ' + 
                                    'Gagal memuat data. Status: ' + status);
            }
        });
    });
    
    // 6. Tombol Delete Confirmation
    $(document).on('click', '.delete-campaign-btn', function(e) {
        e.preventDefault();
        const campaignId = $(this).data('id');
        const campaignTitle = $(this).data('title');
        
        console.log('Delete button clicked for:', campaignTitle);
        
        $('#deleteCampaignTitle').text(campaignTitle);
        $('#deleteConfirmBtn').attr('href', 'campaign.php?delete=' + campaignId);
        deleteCampaignModal.show();
    });
    
    // 7. Add Campaign Form Submission
    $('#addCampaignForm').submit(function(e) {
        e.preventDefault();
        console.log('Add form submitted');
        
        // Validate dates
        const startDate = new Date($('input[name="tgl_mulai"]').val());
        const endDate = new Date($('input[name="tgl_selesai"]').val());
        
        if (endDate <= startDate) {
            $('#addAlert').removeClass('d-none alert-success').addClass('alert-danger')
                         .html('<i class="bi bi-exclamation-triangle me-2"></i>Tanggal selesai harus setelah tanggal mulai');
            return;
        }
        
        // Prepare form data
        const formData = $(this).serializeArray();
        
        // Convert currency values to numbers
        formData.forEach(item => {
            if (item.name === 'target_dana' || item.name === 'dana_terkumpul') {
                item.value = item.value.replace(/\D/g, '');
            }
        });
        
        // Show loading
        const submitBtn = $(this).find('button[type="submit"]');
        const originalText = submitBtn.html();
        submitBtn.html('<span class="spinner-border spinner-border-sm"></span> Menyimpan...');
        submitBtn.prop('disabled', true);
        
        // Submit via AJAX
        $.ajax({
            url: 'campaign-process.php?action=add',
            type: 'POST',
            data: $.param(formData),
            dataType: 'json',
            success: function(response) {
                console.log('Add Campaign Response:', response);
                
                if (response.success) {
                    $('#addAlert').removeClass('d-none alert-danger').addClass('alert-success')
                                 .html('<i class="bi bi-check-circle me-2"></i>' + 
                                       (response.message || 'Campaign berhasil ditambahkan'));
                    
                    // Clear form
                    $('#addCampaignForm')[0].reset();
                    
                    // Reload page after 2 seconds
                    setTimeout(() => {
                        window.location.reload();
                    }, 2000);
                } else {
                    $('#addAlert').removeClass('d-none').addClass('alert-danger')
                                 .html('<i class="bi bi-exclamation-triangle me-2"></i>' + 
                                       (response.message || 'Gagal menambahkan campaign'));
                    submitBtn.html(originalText);
                    submitBtn.prop('disabled', false);
                }
            },
            error: function() {
                $('#addAlert').removeClass('d-none').addClass('alert-danger')
                             .html('<i class="bi bi-exclamation-triangle me-2"></i>Terjadi kesalahan saat menyimpan');
                submitBtn.html(originalText);
                submitBtn.prop('disabled', false);
            }
        });
    });
    
    // 8. Edit Campaign Form Submission
    $('#editCampaignForm').submit(function(e) {
        e.preventDefault();
        console.log('Edit form submitted');
        
        // Validate dates
        const startDate = new Date($('#edit_tgl_mulai').val());
        const endDate = new Date($('#edit_tgl_selesai').val());
        
        if (endDate <= startDate) {
            $('#editAlert').removeClass('d-none alert-success').addClass('alert-danger')
                          .html('<i class="bi bi-exclamation-triangle me-2"></i>Tanggal selesai harus setelah tanggal mulai');
            return;
        }
        
        // Prepare form data
        const formData = $(this).serializeArray();
        
        // Convert currency values to numbers
        formData.forEach(item => {
            if (item.name === 'target_dana' || item.name === 'dana_terkumpul') {
                item.value = item.value.replace(/\D/g, '');
            }
        });
        
        // Show loading
        const submitBtn = $(this).find('button[type="submit"]');
        const originalText = submitBtn.html();
        submitBtn.html('<span class="spinner-border spinner-border-sm"></span> Menyimpan...');
        submitBtn.prop('disabled', true);
        
        // Submit via AJAX
        $.ajax({
            url: 'campaign-process.php?action=edit',
            type: 'POST',
            data: $.param(formData),
            dataType: 'json',
            success: function(response) {
                console.log('Edit Campaign Response:', response);
                
                if (response.success) {
                    $('#editAlert').removeClass('d-none alert-danger').addClass('alert-success')
                                  .html('<i class="bi bi-check-circle me-2"></i>' + 
                                        (response.message || 'Campaign berhasil diupdate'));
                    
                    // Close modal and reload after 2 seconds
                    setTimeout(() => {
                        editCampaignModal.hide();
                        window.location.reload();
                    }, 2000);
                } else {
                    $('#editAlert').removeClass('d-none').addClass('alert-danger')
                                  .html('<i class="bi bi-exclamation-triangle me-2"></i>' + 
                                        (response.message || 'Gagal mengupdate campaign'));
                    submitBtn.html(originalText);
                    submitBtn.prop('disabled', false);
                }
            },
            error: function() {
                $('#editAlert').removeClass('d-none').addClass('alert-danger')
                              .html('<i class="bi bi-exclamation-triangle me-2"></i>Terjadi kesalahan saat menyimpan');
                submitBtn.html(originalText);
                submitBtn.prop('disabled', false);
            }
        });
    });
    
    // 9. Auto-hide alerts after 5 seconds
    setTimeout(() => {
        $('.alert').alert('close');
    }, 5000);
    
    // 10. Focus search input
    $('input[name="search"]').focus();
    
    // 11. DEBUG: Log semua tombol edit yang ditemukan
    $('.edit-campaign-btn').each(function(index) {
        console.log('Edit Button ' + index + ':', $(this).data('id'), $(this).prop('outerHTML'));
    });
});
</script>
</body>
</html>