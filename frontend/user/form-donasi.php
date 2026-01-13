<!DOCTYPE html>
<?php
require_once __DIR__ . '/../../backend/config/session.php';
Session::start();
$isLoggedIn = Session::isLoggedIn();
?>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pilih Nominal - Sistem Donasi</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <style>
    :root {
      --primary-blue: #0d6efd;
      /* Biru Bootstrap sesuai tombol Daftar di index */
      --bg-light: #f8f9fa;
    }

    body {
      background-color: var(--bg-light);
      font-family: 'Poppins', sans-serif;
    }

    /* Navbar Brand Blue */
    .text-blue {
      color: var(--primary-blue) !important;
    }

    /* Card & Design */
    .card-custom {
      border: none;
      border-radius: 15px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    /* Nominal Buttons */
    .nominal-item {
      display: none;
    }

    .nominal-label {
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 20px;
      border: 1.5px solid #dee2e6;
      border-radius: 12px;
      cursor: pointer;
      font-weight: 600;
      background: white;
      transition: 0.2s;
    }

    .nominal-item:checked+.nominal-label {
      border-color: var(--primary-blue);
      color: var(--primary-blue);
      background: rgba(13, 110, 253, 0.05);
    }

    /* Button Lanjutkan */
    .btn-lanjut {
      background: #adb5bd;
      /* Abu-abu sebelum aktif */
      color: white;
      border: none;
      padding: 15px;
      border-radius: 10px;
      width: 100%;
      font-weight: 600;
      transition: 0.3s;
    }

    .btn-lanjut.active {
      background: var(--primary-blue);
      cursor: pointer;
      box-shadow: 0 4px 12px rgba(13, 110, 253, 0.3);
    }

    .info-blue {
      background-color: #eef6ff;
      color: #0d6efd;
      border-radius: 10px;
      padding: 15px;
      font-size: 0.85rem;
    }
  </style>
</head>

<body>

  <div class="container py-5">
    <div class="row">
      <div class="col-lg-8 mb-4">
        <div class="card card-custom p-4">
          <div class="d-flex align-items-center mb-4">
            <a href="javascript:history.back()" class="text-dark me-3"><i class="fas fa-arrow-left"></i></a>
            <div>
              <h5 class="fw-bold mb-0">Form Donasi</h5>
              <small class="text-muted">Bantu 1000 Anak Sekolah</small>
            </div>
          </div>

          <p class="text-muted small mb-4">Langkah 1 dari 2</p>

          <label class="fw-bold mb-3">Pilih Nominal Donasi</label>
          <div class="row g-3 mb-4">
            <?php
            $nominals = [50000, 100000, 250000, 500000, 1000000, 2000000];
            foreach ($nominals as $nom): ?>
              <div class="col-md-4 col-6">
                <input type="radio" name="nominal" id="n<?= $nom ?>" class="nominal-item" value="<?= $nom ?>">
                <label for="n<?= $nom ?>" class="nominal-label">Rp <?= number_format($nom, 0, ',', '.') ?></label>
              </div>
            <?php endforeach; ?>
          </div>

          <div class="mb-4">
            <label class="fw-bold mb-2 small text-uppercase text-muted">Atau Masukkan Nominal Lain</label>
            <div class="input-group">
              <span class="input-group-text border-0 bg-light">Rp</span>
              <input type="number" id="customInput" class="form-control border-0 bg-light p-3" placeholder="0">
            </div>
            <small class="text-muted mt-2 d-block small">Minimal donasi Rp 10.000</small>
          </div>

          <button class="btn-lanjut" id="btnNext" disabled>Lanjutkan</button>
        </div>
      </div>

      <div class="col-lg-4">
        <div class="card card-custom p-3">
          <h6 class="fw-bold mb-3">Ringkasan Donasi</h6>
          <img src="https://images.unsplash.com/photo-1523240795612-9a054b0db644?auto=format&fit=crop&w=400&q=80" class="img-fluid rounded mb-3" style="height: 150px; object-fit: cover;">
          <p class="fw-bold mb-0">Bantu 1000 Anak Sekolah</p>
          <p class="text-muted small mb-3">Kategori: Pendidikan</p>
          <hr>
          <div class="d-flex justify-content-between mb-4">
            <span class="text-muted">Nominal Donasi</span>
            <span class="fw-bold text-blue" id="displayNominal">-</span>
          </div>

          <div class="info-blue">
            <div class="d-flex">
              <i class="fas fa-heart me-2 mt-1"></i>
              <span>Setiap rupiah donasi Anda akan sangat berarti dan disalurkan dengan amanah</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    const btn = document.getElementById('btnNext');
    const display = document.getElementById('displayNominal');
    const radios = document.querySelectorAll('input[name="nominal"]');
    const custom = document.getElementById('customInput');

    function update(val) {
      if (val >= 10000) {
        display.innerText = "Rp " + parseInt(val).toLocaleString('id-ID');
        btn.disabled = false;
        btn.classList.add('active');
      } else {
        display.innerText = "-";
        btn.disabled = true;
        btn.classList.remove('active');
      }
    }

    radios.forEach(r => r.addEventListener('change', (e) => {
      custom.value = '';
      update(e.target.value);
    }));
    custom.addEventListener('input', (e) => {
      radios.forEach(r => r.checked = false);
      update(e.target.value);
    });
    btn.onclick = () => {
      const amount = custom.value || document.querySelector('input[name="nominal"]:checked').value;
      const urlParams = new URLSearchParams(window.location.search);
      const id_kampanye = urlParams.get('id') || 1;
      <?php if ($isLoggedIn): ?>
        window.location.href = `pembayaran.php?nominal=${amount}&id_kampanye=${id_kampanye}`;
      <?php else: ?>
        window.location.href = `donatur.php?amount=${amount}&id_kampanye=${id_kampanye}`;
      <?php endif; ?>
    };
  </script>
</body>

</html>