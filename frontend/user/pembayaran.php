<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Metode Pembayaran - Sistem Donasi</title>
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

    .card-main {
      border: none;
      border-radius: 15px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
    }

    /* Styling List Metode Pembayaran sesuai gambar */
    .payment-option {
      display: none;
    }

    .payment-label {
      display: flex;
      align-items: center;
      padding: 20px;
      border: 1.5px solid #dee2e6;
      border-radius: 12px;
      cursor: pointer;
      transition: all 0.2s;
      background: white;
      margin-bottom: 15px;
    }

    .payment-icon {
      font-size: 24px;
      color: #495057;
      margin-right: 20px;
      width: 40px;
      text-align: center;
    }

    .payment-text h6 {
      margin-bottom: 2px;
      font-weight: 700;
      color: #333;
    }

    .payment-text p {
      margin-bottom: 0;
      font-size: 13px;
      color: #6c757d;
    }

    /* Efek saat dipilih (Border Biru Muda) */
    .payment-option:checked+.payment-label {
      border-color: #add1ff;
      background-color: #f0f7ff;
    }

    .payment-option:checked+.payment-label .payment-icon {
      color: var(--primary-blue);
    }

    .btn-lanjut {
      background-color: #8c8f94;
      /* Default abu-abu */
      color: white;
      border: none;
      border-radius: 8px;
      padding: 12px;
      font-weight: 600;
      width: 100%;
      transition: 0.3s;
    }

    /* Tombol berubah biru jika sudah ada yang dipilih */
    .payment-option:checked~.mt-4 .btn-lanjut {
      background-color: var(--primary-blue);
    }

    .summary-box {
      background-color: #eef6ff;
      border-radius: 10px;
      padding: 15px;
      margin-bottom: 25px;
    }
  </style>
</head>

<body>

  <div class="container py-5">
    <div class="row justify-content-center">
      <div class="col-lg-6">
        <a href="javascript:history.back()" class="text-decoration-none text-muted mb-4 d-inline-block">
          <i class="fas fa-arrow-left me-2"></i> Kembali ke Data Diri
        </a>

        <div class="card card-main p-4 p-md-4 bg-white">
          <div class="mb-4">
            <h5 class="fw-bold mb-1">Detail Donasi</h5>
            <p class="text-muted small">Langkah 3 dari 3</p>
          </div>

          <div class="summary-box d-flex justify-content-between align-items-center">
            <div>
              <small class="text-muted d-block">Total Pembayaran</small>
              <h4 class="fw-bold mb-0 text-primary">Rp <?= number_format($_POST['nominal'] ?? 0, 0, ',', '.') ?></h4>
            </div>
            <i class="fas fa-shield-alt text-primary opacity-50 fa-2x"></i>
          </div>

          <h6 class="fw-bold mb-3">Pilih Metode Pembayaran</h6>

          <form action="donasi-berhasil.php" method="POST">
            <input type="hidden" name="nominal" value="<?= $_POST['nominal'] ?? 0 ?>">
            <input type="hidden" name="nama" value="<?= $_POST['nama'] ?? '' ?>">
            <input type="hidden" name="email" value="<?= $_POST['email'] ?? '' ?>">
            <input type="hidden" name="telepon" value="<?= $_POST['telepon'] ?? '' ?>">

            <input type="radio" name="metode" id="bank" class="payment-option" value="Transfer Bank" required>
            <label for="bank" class="payment-label">
              <div class="payment-icon"><i class="fas fa-university"></i></div>
              <div class="payment-text">
                <h6>Transfer Bank</h6>
                <p>BCA, Mandiri, BNI, BRI</p>
              </div>
            </label>

            <input type="radio" name="metode" id="ewallet" class="payment-option" value="E-Wallet">
            <label for="ewallet" class="payment-label">
              <div class="payment-icon"><i class="fas fa-wallet"></i></div>
              <div class="payment-text">
                <h6>E-Wallet</h6>
                <p>GoPay, OVO, Dana, ShopeePay</p>
              </div>
            </label>

            <input type="radio" name="metode" id="credit" class="payment-option" value="Kartu Kredit">
            <label for="credit" class="payment-label">
              <div class="payment-icon"><i class="fas fa-credit-card"></i></div>
              <div class="payment-text">
                <h6>Kartu Kredit/Debit</h6>
                <p>Visa, Mastercard</p>
              </div>
            </label>

            <input type="radio" name="metode" id="tunai" class="payment-option" value="Tunai">
            <label for="tunai" class="payment-label">
              <div class="payment-icon"><i class="fas fa-money-bill-wave"></i></div>
              <div class="payment-text">
                <h6>Tunai</h6>
                <p>Bayar di kantor kami</p>
              </div>
            </label>

            <div class="mt-4">
              <button type="submit" class="btn-lanjut">Konfirmasi Donasi</button>
            </div>
          </form>
        </div>

        <div class="text-center mt-4">
          <p class="text-muted small">ID Transaksi: <span class="fw-bold">DON-<?= time() ?></span></p>
        </div>
      </div>
    </div>
  </div>

</body>

</html>