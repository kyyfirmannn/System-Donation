<?php include 'header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-0">Manajemen Penerima Manfaat</h2>
        <p class="text-muted small">Kelola organisasi penerima donasi</p>
    </div>
    <button class="btn btn-dark px-4 py-2">
        <i class="bi bi-plus-lg me-2"></i> Tambah Penerima Manfaat
    </button>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card shadow-sm text-center p-3 border-0">
            <small class="text-muted d-block">Total Penerima Manfaat</small>
            <h2 class="fw-bold mb-0">5</h2>
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
    <div class="col-md-6">
        <div class="card shadow-sm h-100 p-4 border-0">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div class="d-flex align-items-center">
                    <div class="bg-success bg-opacity-10 p-3 rounded-3 me-3 text-success">
                        <i class="bi bi-bank fs-4"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0">Yayasan Pendidikan Harapan Bangsa</h5>
                        <small class="text-muted small">ID: 1</small>
                    </div>
                </div>
                <span class="badge bg-light text-dark fw-normal border">2 Campaign</span>
            </div>
            
            <p class="text-muted small mb-3">Yayasan yang fokus pada pendidikan anak-anak kurang mampu</p>
            
            <div class="bg-light p-2 rounded mb-4 d-flex align-items-center">
                <i class="bi bi-telephone me-2 text-muted small"></i>
                <small class="text-muted small">021-12345678 | harapanbangsa@email.com</small>
            </div>
            
            <div class="row g-2 mb-4">
                <div class="col-6 text-center border-end">
                    <div class="fw-bold text-primary">2</div>
                    <small class="text-muted small">Campaign Aktif</small>
                </div>
                <div class="col-6 text-center">
                    <div class="fw-bold text-success">2</div>
                    <small class="text-muted small">Total Campaign</small>
                </div>
            </div>
            
            <div class="mb-4">
                <small class="text-muted d-block">Total Dana Diterima</small>
                <div class="fw-bold text-success fs-5">Rp 425.000.000</div>
            </div>

            <div class="d-flex gap-2 mt-auto">
                <button class="btn btn-outline-secondary btn-sm w-50 py-2"><i class="bi bi-pencil me-1"></i> Edit</button>
                <button class="btn btn-outline-danger btn-sm w-50 py-2"><i class="bi bi-trash me-1"></i> Hapus</button>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card shadow-sm h-100 p-4 border-0">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div class="d-flex align-items-center">
                    <div class="bg-success bg-opacity-10 p-3 rounded-3 me-3 text-success">
                        <i class="bi bi-bank fs-4"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0">RS Kasih Ibu</h5>
                        <small class="text-muted small">ID: 2</small>
                    </div>
                </div>
                <span class="badge bg-light text-dark fw-normal border">1 Campaign</span>
            </div>
            
            <p class="text-muted small mb-3">Rumah sakit yang melayani masyarakat kurang mampu</p>
            
            <div class="bg-light p-2 rounded mb-4 d-flex align-items-center">
                <i class="bi bi-telephone me-2 text-muted small"></i>
                <small class="text-muted small">021-87654321 | rskasih@email.com</small>
            </div>
            
            <div class="row g-2 mb-4">
                <div class="col-6 text-center border-end">
                    <div class="fw-bold text-primary">1</div>
                    <small class="text-muted small">Campaign Aktif</small>
                </div>
                <div class="col-6 text-center">
                    <div class="fw-bold text-success">1</div>
                    <small class="text-muted small">Total Campaign</small>
                </div>
            </div>
            
            <div class="mb-4">
                <small class="text-muted d-block">Total Dana Diterima</small>
                <div class="fw-bold text-success fs-5">Rp 180.000.000</div>
            </div>

            <div class="d-flex gap-2 mt-auto">
                <button class="btn btn-outline-secondary btn-sm w-50 py-2"><i class="bi bi-pencil me-1"></i> Edit</button>
                <button class="btn btn-outline-danger btn-sm w-50 py-2"><i class="bi bi-trash me-1"></i> Hapus</button>
            </div>
        </div>
    </div>
</div>