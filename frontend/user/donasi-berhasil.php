<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Donasi Berhasil - Sistem Donasi</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <style>
    body {
      background-color: #f4f7fe;
      font-family: 'Poppins', sans-serif;
      height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .card-success {
      border: none;
      border-radius: 25px;
      box-shadow: 0 10px 40px rgba(0, 0, 0, 0.05);
      max-width: 450px;
      width: 100%;
      padding: 40px 30px;
    }

    .success-icon {
      width: 80px;
      height: 80px;
      background-color: #10b981;
      color: white;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 40px;
      margin: 0 auto 25px;
    }

    .amount-text {
      color: #0d6efd;
      font-weight: 700;
    }

    .alert-confirm {
      background-color: #ecfdf5;
      border: 1px solid #a7f3d0;
      color: #065f46;
      border-radius: 12px;
      padding: 15px;
      font-size: 0.9rem;
      margin-top: 30px;
      margin-bottom: 30px;
    }

    .btn-return {
      background-color: #050505;
      color: white;
      border-radius: 12px;
      padding: 15px;
      width: 100%;
      font-weight: 600;
      border: none;
      transition: 0.3s;
      text-decoration: none;
      display: inline-block;
    }

    .btn-return:hover {
      background-color: #222;
      color: white;
      transform: translateY(-2px);
    }
  </style>
</head>

<body>

  <div class="card card-success bg-white text-center">
    <div class="success-icon">
      <i class="fas fa-check"></i>
    </div>

    <h2 class="fw-bold mb-3">Donasi Berhasil!</h2>

    <p class="text-muted">
      Terima kasih atas donasi Anda sebesar
      <span class="amount-text">Rp <?= number_format($_REQUEST['nominal'] ?? 2000000, 0, ',', '.') ?></span>
    </p>

    <div class="alert-confirm">
      Konfirmasi donasi telah dikirim ke email Anda. Dana akan segera disalurkan kepada penerima manfaat.
    </div>

    <a href="index.php" class="btn-return">
      Kembali ke Halaman Campaign
    </a>
  </div>

</body>

</html>