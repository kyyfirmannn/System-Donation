<?php
// frontend/admin/penerima.php
require_once __DIR__ . '/../../backend/config/database.php';
require_once __DIR__ . '/../../backend/models/CampaignModel.php';
require_once __DIR__ . '/../../backend/models/OrganizationModel.php';
require_once __DIR__ . '/../../backend/models/DonationModel.php';

$organizationModel = new OrganizationModel();

// Handle delete organization
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    if ($organizationModel->deleteOrganization($id)) {
        $_SESSION['success'] = "Organisasi berhasil dihapus";
    } else {
        $_SESSION['error'] = "Gagal menghapus organisasi";
    }
    header('Location: penerima.php');
    exit;
}

// Get all organizations
$organizations = $organizationModel->getAllOrganizations();
$totalOrganizations = count($organizations);
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
                <h2 class="fw-bold mb-0">Manajemen Penerima Manfaat</h2>
                <p class="text-muted small">Kelola organisasi penerima donasi</p>
            </div>
            <button class="btn btn-dark px-4 py-2" id="addOrganizationBtn">
                <i class="bi bi-plus-lg me-2"></i> Tambah Penerima Manfaat
            </button>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card shadow-sm text-center p-3 border-0">
                    <small class="text-muted d-block">Total Penerima Manfaat</small>
                    <h2 class="fw-bold mb-0"><?php echo $totalOrganizations; ?></h2>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm text-center p-3 border-0">
                    <small class="text-muted d-block text-success">Campaign Aktif</small>
                    <h2 class="fw-bold mb-0 text-success">5</h2>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm text-center p-3 border-0">
                    <small class="text-muted d-block text-primary">Total Disalurkan</small>
                    <h2 class="fw-bold mb-0 text-primary">Rp 1.225.000.000</h2>
                </div>
            </div>
        </div>

        <div class="input-group mb-4 shadow-sm rounded">
            <span class="input-group-text bg-white border-0"><i class="bi bi-search text-muted"></i></span>
            <input type="text" class="form-control border-0 py-2" placeholder="Cari penerima manfaat...">
        </div>

        <div class="row g-4">
            <?php foreach ($organizations as $org): ?>
            <div class="col-md-6">
                <div class="card shadow-sm h-100 p-4 border-0">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="d-flex align-items-center">
                            <div class="bg-success bg-opacity-10 p-3 rounded-3 me-3 text-success">
                                <i class="bi bi-bank fs-4"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold mb-0"><?php echo htmlspecialchars($org['nama_organisasi']); ?></h5>
                                <small class="text-muted small">ID: <?php echo $org['id_organisasi']; ?></small>
                            </div>
                        </div>
                        <span class="badge bg-light text-dark fw-normal border">Campaign</span>
                    </div>
                    
                    <p class="text-muted small mb-3"><?php echo htmlspecialchars($org['alamat'] ?? 'Alamat tidak tersedia'); ?></p>
                    
                    <div class="bg-light p-2 rounded mb-4 d-flex align-items-center">
                        <i class="bi bi-telephone me-2 text-muted small"></i>
                        <small class="text-muted small"><?php echo htmlspecialchars($org['no_kontak'] ?? 'N/A'); ?> | <?php echo htmlspecialchars($org['email_kontak'] ?? 'N/A'); ?></small>
                    </div>
                    
                    <div class="row g-2 mb-4">
                        <div class="col-6 text-center border-end">
                            <div class="fw-bold text-primary">0</div>
                            <small class="text-muted small">Campaign Aktif</small>
                        </div>
                        <div class="col-6 text-center">
                            <div class="fw-bold text-success">0</div>
                            <small class="text-muted small">Total Campaign</small>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <small class="text-muted d-block">Total Dana Diterima</small>
                        <div class="fw-bold text-success fs-5">Rp 0</div>
                    </div>

                    <div class="d-flex gap-2 mt-auto">
                        <button class="btn btn-outline-secondary btn-sm w-50 py-2 edit-organization-btn" 
                                data-id="<?php echo $org['id_organisasi']; ?>" 
                                data-nama="<?php echo htmlspecialchars($org['nama_organisasi']); ?>" 
                                data-alamat="<?php echo htmlspecialchars($org['alamat'] ?? ''); ?>" 
                                data-email="<?php echo htmlspecialchars($org['email_kontak'] ?? ''); ?>" 
                                data-no="<?php echo htmlspecialchars($org['no_kontak'] ?? ''); ?>">
                            <i class="bi bi-pencil me-1"></i> Edit
                        </button>
                        <button class="btn btn-outline-danger btn-sm w-50 py-2 delete-organization-btn" 
                                data-id="<?php echo $org['id_organisasi']; ?>" 
                                data-nama="<?php echo htmlspecialchars($org['nama_organisasi']); ?>">
                            <i class="bi bi-trash me-1"></i> Hapus
                        </button>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Modal for Create/Edit Organization -->
        <div class="modal fade" id="organizationModal" tabindex="-1" aria-labelledby="organizationModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="organizationModalLabel">Tambah Penerima Manfaat</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div id="modalAlert" class="alert d-none mx-3 mt-3" role="alert"></div>
                    <form id="organizationForm">
                        <div class="modal-body">
                            <input type="hidden" name="id_organisasi" id="modalId">
                            
                            <div class="mb-3">
                                <label for="nama_organisasi" class="form-label">Nama Organisasi <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="nama_organisasi" name="nama_organisasi" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="alamat" class="form-label">Alamat</label>
                                <textarea class="form-control" id="alamat" name="alamat" rows="3"></textarea>
                            </div>
                            
                            <div class="mb-3">
                                <label for="email_kontak" class="form-label">Email Kontak</label>
                                <input type="email" class="form-control" id="email_kontak" name="email_kontak">
                            </div>
                            
                            <div class="mb-3">
                                <label for="no_kontak" class="form-label">No. Kontak</label>
                                <input type="text" class="form-control" id="no_kontak" name="no_kontak">
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

        <!-- Modal for Delete Confirmation -->
        <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Apakah Anda yakin ingin menghapus organisasi <strong id="deleteName"></strong>?</p>
                        <p class="text-danger small">Tindakan ini tidak dapat dibatalkan.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-danger" onclick="confirmDelete()">Hapus</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</main>
 <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
$(document).ready(function() {
    console.log('Document ready - initializing modals');
    
    // Initialize modals
    const addOrganizationModalEl = document.getElementById('organizationModal');
    const addOrganizationModal = new bootstrap.Modal(addOrganizationModalEl);
    const deleteOrganizationModalEl = document.getElementById('deleteModal');
    const deleteOrganizationModal = new bootstrap.Modal(deleteOrganizationModalEl);
    
    console.log('Modals initialized:', {
        addModal: !!addOrganizationModalEl,
        deleteModal: !!deleteOrganizationModalEl
    });

    // Add Organization Button
    $('#addOrganizationBtn').click(function(e) {
        e.preventDefault();
        console.log('Add organization button clicked');
        
        // Reset form
        document.getElementById('organizationForm').reset();
        document.getElementById('modalId').value = '';
        document.getElementById('organizationModalLabel').textContent = 'Tambah Penerima Manfaat';
        document.getElementById('modalAlert').classList.add('d-none');
        
        addOrganizationModal.show();
    });

    // Edit Organization Button (using event delegation)
    $(document).on('click', '.edit-organization-btn', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        const id = $(this).data('id');
        const nama = $(this).data('nama');
        const alamat = $(this).data('alamat');
        const email = $(this).data('email');
        const no = $(this).data('no');
        
        console.log('Edit organization button clicked for ID:', id);
        
        // Fill form
        document.getElementById('modalId').value = id;
        document.getElementById('nama_organisasi').value = nama;
        document.getElementById('alamat').value = alamat;
        document.getElementById('email_kontak').value = email;
        document.getElementById('no_kontak').value = no;
        document.getElementById('organizationModalLabel').textContent = 'Edit Penerima Manfaat';
        document.getElementById('modalAlert').classList.add('d-none');
        
        addOrganizationModal.show();
    });

    // Delete Organization Button (using event delegation)
    $(document).on('click', '.delete-organization-btn', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        const id = $(this).data('id');
        const nama = $(this).data('nama');
        
        console.log('Delete organization button clicked for ID:', id);
        
        // Set delete data
        window.deleteOrganizationId = id;
        document.getElementById('deleteName').textContent = nama;
        
        deleteOrganizationModal.show();
    });

    // Confirm Delete Button
    window.confirmDelete = function() {
        if (window.deleteOrganizationId) {
            window.location.href = 'penerima.php?delete=' + window.deleteOrganizationId;
        }
    };

    // Form submission with AJAX
    $('#organizationForm').submit(function(e) {
        e.preventDefault();
        console.log('Form submitted');
        
        const formData = new FormData(this);
        const isEdit = formData.get('id_organisasi') !== '';
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
            url: 'penerima-process.php?action=' + action,
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
                        $('#organizationForm')[0].reset();
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
    
    console.log('All event handlers attached');
});
</script>

<?php include 'footer.php'; ?>