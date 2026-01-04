<?php include 'header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-0">Manajemen Campaign</h2>
        <p class="text-muted">Kelola semua campaign donasi</p>
    </div>
    <button class="btn btn-dark px-4 py-2">
        <i class="bi bi-plus-lg me-2"></i> Tambah Campaign
    </button>
</div>

<div class="row g-3 mb-4 text-center">
    <div class="col-md-4">
        <div class="card shadow-sm p-4">
            <small class="text-muted d-block mb-1">Total Campaign</small>
            <h2 class="fw-bold mb-0">6</h2>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm p-4">
            <small class="text-muted d-block mb-1 text-success">Campaign Aktif</small>
            <h2 class="fw-bold mb-0 text-success">5</h2>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm p-4">
            <small class="text-muted d-block mb-1 text-primary">Total Terkumpul</small>
            <h2 class="fw-bold mb-0 text-primary">Rp 1.225.000.000</h2>
        </div>
    </div>
</div>

<div class="input-group mb-4 shadow-sm rounded">
    <span class="input-group-text bg-white border-0"><i class="bi bi-search text-muted"></i></span>
    <input type="text" class="form-control border-0 py-2" placeholder="Cari campaign...">
</div>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="p-3 border-bottom">
            <h6 class="fw-bold mb-1">Daftar Campaign</h6>
            <small class="text-muted">Menampilkan 6 campaign</small>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-muted">
                    <tr>
                        <th class="ps-3 border-0">Campaign</th>
                        <th class="border-0 text-center">Kategori</th>
                        <th class="border-0">Progress</th>
                        <th class="border-0">Target</th>
                        <th class="border-0 text-center">Status</th>
                        <th class="border-0 text-end pe-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="ps-3">
                            <div class="fw-bold mb-0">Bantu 1000 Anak Sekolah</div>
                            <small class="text-muted">Program bantuan biaya pendidikan untuk 1000 an...</small>
                        </td>
                        <td class="text-center">
                            <span class="badge rounded-pill border text-dark fw-normal px-3 py-2">Pendidikan</span>
                        </td>
                        <td>
                            <small class="d-flex justify-content-between">
                                <span>70%</span>
                                <span class="text-muted small">Rp 350.000.000</span>
                            </small>
                            <div class="progress mt-1" style="height: 6px;">
                                <div class="progress-bar bg-dark" style="width: 70%"></div>
                            </div>
                        </td>
                        <td class="fw-bold small">Rp 500.000.000</td>
                        <td class="text-center">
                            <span class="badge bg-dark px-3 py-2">aktif</span>
                        </td>
                        <td class="text-end pe-3">
                            <button class="btn btn-sm btn-light border"><i class="bi bi-eye"></i></button>
                            <button class="btn btn-sm btn-light border mx-1"><i class="bi bi-pencil"></i></button>
                            <button class="btn btn-sm btn-light border text-danger"><i class="bi bi-trash"></i></button>
                        </td>
                    </tr>
                    </tbody>
            </table>
        </div>
    </div>
</div>

<?php // Tutup container dari header.php ?>
</div> 
</body>
</html>