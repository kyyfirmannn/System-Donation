<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Data Donatur - Sistem Donasi</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <style>
    :root {
      --primary-blue: #0d6efd;
    }

    body {
      background-color: #f8f9fa;
      font-family: 'Poppins', sans-serif;
    }

    .card-donatur {
      border: none;
      border-radius: 20px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
    }

    .amount-badge {
      background: rgba(13, 110, 253, 0.08);
      color: var(--primary-blue);
      padding: 20px;
      border-radius: 12px;
      border: 1px dashed var(--primary-blue);
    }

    .form-control-custom {
      background-color: #f1f3f5;
      border: none;
      padding: 15px;
      border-radius: 10px;
      transition: all 0.3s;
    }

    .form-control-custom:focus {
      background-color: #e9ecef;
      box-shadow: none;
      border: 1px solid var(--primary-blue);
    }

    .btn-primary-blue {
      background-color: var(--primary-blue);
      color: white;
      border: none;
      border-radius: 12px;
      padding: 15px;
      font-weight: 600;
      transition: 0.3s;
    }

    .btn-primary-blue:hover {
      background-color: #0b5ed7;
      transform: translateY(-2px);
    }
  </style>
</head>

<body>

  <div class="container py-5">
    <div class="row justify-content-center">
      <div class="col-lg-6">
        <a href="form-donasi.php" class="text-decoration-none text-muted mb-4 d-inline-block">
          <i class="fas fa-arrow-left me-2"></i> Kembali pilih nominal
        </a>

        <div class="card card-donatur p-4 p-md-5 bg-white">
          <div class="text-center mb-4">
            <h4 class="fw-bold text-dark">Lengkapi Data Diri</h4>
            <p class="text-muted small">Informasi ini diperlukan untuk verifikasi donasi</p>
          </div>

          <div class="amount-badge text-center mb-4">
            <small class="d-block text-muted mb-1 text-uppercase fw-bold" style="letter-spacing: 1px;">Donasi Anda</small>
            <h2 class="fw-bold mb-0">Rp <?= number_format($_GET['amount'] ?? 0, 0, ',', '.') ?></h2>
          </div>

          <form action="pembayaran.php" method="POST">
            <input type="hidden" name="nominal" value="<?= $_GET['amount'] ?? 0 ?>">

            <div class="mb-3">
              <label class="form-label small fw-bold text-muted">NAMA LENGKAP</label>
              <div class="input-group">
                <span class="input-group-text border-0 bg-light"><i class="fas fa-user text-muted"></i></span>
                <input type="text" name="nama" class="form-control form-control-custom" placeholder="Contoh: Ahmad Subarjo" required>
              </div>
            </div>

            <div class="mb-3">
              <label class="form-label small fw-bold text-muted">ALAMAT EMAIL</label>
              <div class="input-group">
                <span class="input-group-text border-0 bg-light"><i class="fas fa-envelope text-muted"></i></span>
                <input type="email" name="email" class="form-control form-control-custom" placeholder="nama@email.com" required>
              </div>
            </div>

            <div class="mb-4">
              <label class="form-label small fw-bold text-muted">NOMOR TELEPON / WHATSAPP</label>
              <div class="input-group">
                <span class="input-group-text border-0 bg-light"><i class="fab fa-whatsapp text-muted"></i></span>
                <input type="tel" name="telepon" class="form-control form-control-custom" placeholder="Contoh: 081234567890" required>
              </div>
            </div>

            <div class="form-check form-switch mb-5">
              <input class="form-check-input" type="checkbox" name="anonim" id="anonimCheck">
              <label class="form-check-label small text-muted" for="anonimCheck">Sembunyikan nama saya (Donasi sebagai Anonim)</label>
            </div>

            <button type="submit" class="btn btn-primary-blue w-100 shadow-sm">
              Lanjutkan Pembayaran <i class="fas fa-arrow-right ms-2"></i>
            </button>
          </form>
        </div>

        <div class="text-center mt-4">
          <p class="text-muted small">
            <i class="fas fa-lock me-1 text-primary"></i> Data Anda terlindungi dengan enkripsi SSL aman.
          </p>
        </div>
      </div>
    </div>
  </div>

</body>

</html>