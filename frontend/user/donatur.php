<?php
// Tangkap halaman tujuan jika ada (misal dari detail.php)
$redirect = isset($_GET['redirect']) ? $_GET['redirect'] : 'index.php';
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - Sistem Donasi</title>
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

    .card-login {
      border: none;
      border-radius: 20px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
      overflow: hidden;
    }

    .login-header {
      background: var(--primary-blue);
      color: white;
      padding: 40px;
      text-align: center;
    }

    .form-control-custom {
      background-color: #f1f3f5;
      border: none;
      padding: 12px 15px;
      border-radius: 10px;
    }

    .btn-login {
      background-color: var(--primary-blue);
      color: white;
      border: none;
      border-radius: 10px;
      padding: 12px;
      font-weight: 600;
      width: 100%;
      transition: 0.3s;
    }

    .btn-login:hover {
      background-color: #0b5ed7;
      transform: translateY(-2px);
    }
  </style>
</head>

<body>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-5">
        <div class="card card-login bg-white">
          <div class="login-header">
            <h3 class="fw-bold mb-0">Selamat Datang</h3>
            <p class="small mb-0 opacity-75">Masuk untuk melanjutkan aksi baikmu</p>
          </div>
          <div class="card-body p-4 p-md-5">
            <?php if (isset($_GET['registered'])): ?>
              <div class="alert alert-success">Registrasi berhasil. Silakan login.</div>
            <?php elseif (isset($_GET['error'])): ?>
              <div class="alert alert-danger">Email atau password salah.</div>
            <?php endif; ?>

            <form action="proses-login.php" method="POST">
              <input type="hidden" name="redirect" value="<?php echo $redirect; ?>">
              <?php if (isset($_GET['amount'])): ?>
                <input type="hidden" name="amount" value="<?php echo htmlspecialchars($_GET['amount']); ?>">
              <?php endif; ?>
              <div class="mb-3">
                <label class="form-label small fw-bold text-muted">EMAIL</label>
                <input type="email" name="email" class="form-control form-control-custom" placeholder="nama@email.com" required>
              </div>
              <div class="mb-4">
                <label class="form-label small fw-bold text-muted">PASSWORD</label>
                <input type="password" name="password" class="form-control form-control-custom" placeholder="••••••••" required>
              </div>
              <button type="submit" class="btn btn-login shadow-sm mb-3">Masuk Sekarang</button>
              <div class="text-center">
                <p class="small text-muted">Belum punya akun? <a href="daftar.php?redirect=<?php echo urlencode($redirect); ?>" class="text-decoration-none fw-bold">Daftar di sini</a></p>
                <a href="<?php echo $redirect; ?>" class="small text-decoration-none text-muted"><i class="fas fa-arrow-left me-1"></i> Kembali ke Beranda</a>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>

</html>