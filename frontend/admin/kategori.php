<?php include 'header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-0">Manajemen Kategori</h2>
        <p class="text-muted">Kelola kategori campaign donasi</p>
    </div>
    <button class="btn btn-dark px-4 py-2">
        <i class="bi bi-plus-lg me-2"></i> Tambah Kategori
    </button>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card shadow-sm text-center p-3">
            <small class="text-muted d-block">Total Kategori</small>
            <h2 class="fw-bold mb-0">5</h2>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm text-center p-3">
            <small class="text-muted d-block text-success">Total Campaign</small>
            <h2 class="fw-bold mb-0 text-success">6</h2>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm text-center p-3">
            <small class="text-muted d-block text-primary">Campaign Aktif</small>
            <h2 class="fw-bold mb-0 text-primary">5</h2>
        </div>
    </div>
</div>

<div class="input-group mb-4 shadow-sm rounded">
    <span class="input-group-text bg-white border-0"><i class="bi bi-search text-muted"></i></span>
    <input type="text" class="form-control border-0 py-2" placeholder="Cari kategori...">
</div>

<div class="row g-4">
    <div class="col-md-4">
        <div class="card shadow-sm h-100 p-4">
            <div class="d-flex align-items-start mb-3">
                <div class="bg-primary bg-opacity-10 p-3 rounded-3 me-3">
                    <i class="bi bi-tag-fill text-primary fs-4"></i>
                </div>
                <div>
                    <h5 class="fw-bold mb-0">Pendidikan</h5>
                    <small class="text-muted small">ID: 1</small>
                </div>
            </div>
            <p class="text-muted small mb-4">Program bantuan untuk pendidikan anak-anak dan pemuda</p>
            
            <div class="row g-2 mb-4">
                <div class="col-6">
                    <div class="bg-light p-2 rounded text-center">
                        <div class="fw-bold">2</div>
                        <small class="text-muted" style="font-size: 10px;">Total Campaign</small>
                    </div>
                </div>
                <div class="col-6">
                    <div class="bg-light p-2 rounded text-center">
                        <div class="fw-bold text-primary">2</div>
                        <small class="text-muted" style="font-size: 10px;">Campaign Aktif</small>
                    </div>
                </div>
            </div>
            
            <div class="mb-4">
                <small class="text-muted d-block">Total Dana Terkumpul</small>
                <div class="fw-bold text-success">Rp 425.000.000</div>
            </div>

            <div class="d-flex gap-2 mt-auto">
                <button class="btn btn-outline-secondary btn-sm w-50 py-2"><i class="bi bi-pencil me-1"></i> Edit</button>
                <button class="btn btn-outline-danger btn-sm w-50 py-2"><i class="bi bi-trash me-1"></i> Hapus</button>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow-sm h-100 p-4 border-0">
            <div class="d-flex align-items-start mb-3">
                <div class="bg-primary bg-opacity-10 p-3 rounded-3 me-3">
                    <i class="bi bi-tag-fill text-primary fs-4"></i>
                </div>
                <div>
                    <h5 class="fw-bold mb-0">Kesehatan</h5>
                    <small class="text-muted small">ID: 2</small>
                </div>
            </div>
            <p class="text-muted small mb-4">Bantuan medis dan kesehatan masyarakat</p>
            
            <div class="row g-2 mb-4">
                <div class="col-6">
                    <div class="bg-light p-2 rounded text-center">
                        <div class="fw-bold">1</div>
                        <small class="text-muted" style="font-size: 10px;">Total Campaign</small>
                    </div>
                </div>
                <div class="col-6">
                    <div class="bg-light p-2 rounded text-center">
                        <div class="fw-bold text-primary">1</div>
                        <small class="text-muted" style="font-size: 10px;">Campaign Aktif</small>
                    </div>
                </div>
            </div>
            
            <div class="mb-4">
                <small class="text-muted d-block">Total Dana Terkumpul</small>
                <div class="fw-bold text-success">Rp 180.000.000</div>
            </div>

            <div class="d-flex gap-2 mt-auto">
                <button class="btn btn-outline-secondary btn-sm w-50 py-2"><i class="bi bi-pencil me-1"></i> Edit</button>
                <button class="btn btn-outline-danger btn-sm w-50 py-2"><i class="bi bi-trash me-1"></i> Hapus</button>
            </div>
        </div>
    </div>

    </div>
