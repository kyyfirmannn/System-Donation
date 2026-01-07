<?php
$redirect = isset($_GET['redirect']) ? $_GET['redirect'] : 'index.php';
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Daftar Akun - Sistem Donasi</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <style>
    :root {
      --primary-blue: #0d6efd;
    }

    body {
      background-color: #f8f9fa;
      font-family: 'Poppins', sans-serif;
      min-height: 100vh;
      display: flex;
      align-items: center;
    }

    .card-register {
      border: none;
      border-radius: 20px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
    }

    .form-control-custom {
      background-color: #f1f3f5;
      border: none;
      padding: 12px 15px;
      border-radius: 10px;
    }

    .btn-register {
      background-color: var(--primary-blue);
      color: white;
      border: none;
      border-radius: 10px;
      padding: 12px;
      font-weight: 600;
      width: 100%;
      transition: 0.3s;
    }

    .btn-register:hover {
      background-color: #0b5ed7;
      transform: translateY(-2px);
    }
  </style>
</head>

<body>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-5">
        <div class="card card-register p-4 p-md-5 bg-white">
          <div class="text-center mb-4">
            <h3 class="fw-bold text-dark">Daftar Akun</h3>
            <p class="text-muted small">Mulai berbagi kebaikan hari ini</p>
          </div>
          <form action="proses-daftar.php" method="POST">
            <input type="hidden" name="redirect" value="<?php echo $redirect; ?>">
            <?php if (isset($_GET['error'])): ?>
              <div class="alert alert-danger">Terjadi kesalahan saat mendaftar. Coba lagi.</div>
            <?php endif; ?>
            <div class="mb-3">
              <label class="form-label small fw-bold text-muted">NAMA LENGKAP</label>
              <input type="text" name="nama" class="form-control form-control-custom" placeholder="Contoh: Budi Santoso" required>
            </div>
            <div class="mb-3">
              <label class="form-label small fw-bold text-muted">ALAMAT EMAIL</label>
              <input type="email" name="email" class="form-control form-control-custom" placeholder="nama@email.com" required>
            </div>
            <div class="mb-4">
              <label class="form-label small fw-bold text-muted">PASSWORD</label>
              <input type="password" name="password" class="form-control form-control-custom" placeholder="Buat password kuat" required>
            </div>
            <button type="submit" class="btn btn-register shadow-sm mb-3">Buat Akun Sekarang</button>
            <div class="text-center">
              <p class="small text-muted">Sudah punya akun? <a href="login.php?redirect=<?php echo urlencode($redirect); ?>" class="text-decoration-none fw-bold">Login di sini</a></p>
              <hr>
              <a href="<?php echo $redirect; ?>" class="small text-decoration-none text-muted">Nanti saja, kembali ke Beranda</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</body>

</html>