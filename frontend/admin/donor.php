<?php include 'header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-0">Manajemen Donor</h2>
        <p class="text-muted">Kelola data donor dan kontributor</p>
    </div>
    <button class="btn btn-dark px-4 py-2">
        <i class="bi bi-plus-lg me-2"></i> Tambah Donor
    </button>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card shadow-sm p-4 border-0 text-center">
            <small class="text-muted d-block mb-1">Total Donor</small>
            <h2 class="fw-bold mb-0">8</h2>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm p-4 border-0 text-center text-success">
            <small class="text-muted d-block mb-1">Total Donasi</small>
            <h2 class="fw-bold mb-0">9</h2>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm p-4 border-0 text-center text-primary">
            <small class="text-muted d-block mb-1">Total Kontribusi</small>
            <h2 class="fw-bold mb-0">Rp 33.500.000</h2>
        </div>
    </div>
</div>

<div class="input-group mb-4 shadow-sm rounded">
    <span class="input-group-text bg-white border-0 ps-3"><i class="bi bi-search text-muted"></i></span>
    <input type="text" class="form-control border-0 py-2" placeholder="Cari donor berdasarkan nama atau email...">
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="p-3 border-bottom">
            <h6 class="fw-bold mb-1">Daftar Donor</h6>
            <small class="text-muted">Menampilkan 8 donor</small>
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
                    <tr>
                        <td class="ps-3">
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">B</div>
                                <div>
                                    <div class="fw-bold">Budi Santoso</div>
                                    <div class="text-muted" style="font-size: 11px;">ID: 1</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="text-muted mb-1"><i class="bi bi-envelope me-1"></i> budi.santoso@email.com</div>
                            <div class="text-muted"><i class="bi bi-telephone me-1"></i> 081234567890</div>
                        </td>
                        <td class="text-muted" style="max-width: 200px;">Jl. Merdeka No. 123, Jakarta Pusat</td>
                        <td>15 Januari 2024</td>
                        <td>
                            <div class="fw-bold text-primary">Rp 5.000.000</div>
                            <div class="text-muted small">1 donasi</div>
                        </td>
                        <td class="text-end pe-3">
                            <button class="btn btn-sm btn-light border mx-1"><i class="bi bi-pencil"></i></button>
                            <button class="btn btn-sm btn-light border text-danger"><i class="bi bi-trash"></i></button>
                        </td>
                    </tr>
                    </tbody>
            </table>
        </div>
    </div>
</div>
