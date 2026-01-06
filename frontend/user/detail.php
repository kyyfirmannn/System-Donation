<?php
// 1. Tangkap ID dari URL (contoh: detail.php?id=2)
$id_kampanye = isset($_GET['id']) ? $_GET['id'] : '1';

// 2. Data Simulasi (Database Mockup)
$database_kampanye = [
  '1' => [
    'judul'     => 'Bantu Pendidikan Anak Yatim',
    'kategori'  => 'Pendidikan',
    'gambar'    => 'https://images.unsplash.com/photo-1523240795612-9a054b0db644?ixlib=rb-1.2.1&auto=format&fit=crop&w=1200&q=80',
    'yayasan'   => 'Yayasan Pendidikan Harapan Bangsa',
    'deskripsi_yayasan' => 'Yayasan yang fokus pada pendidikan anak-anak kurang mampu',
    'kontak'    => '021-12345678 | harapanbangsa@email.com',
    'target'    => 'Rp 50.000.000',
    'terkumpul' => 'Rp 37.500.000',
    'persen'    => '75',
    'donatur'   => '125',
    'sisa_hari' => '15'
  ],
  '2' => [
    'judul'     => 'Operasi Jantung untuk Bayi',
    'kategori'  => 'Kesehatan',
    'gambar'    => 'https://images.unsplash.com/photo-1576765974257-b414b9ea0051?ixlib=rb-1.2.1&auto=format&fit=crop&w=1200&q=80',
    'yayasan'   => 'Klinik Sehat Utama',
    'deskripsi_yayasan' => 'Membantu biaya medis bagi keluarga prasejahtera',
    'kontak'    => '021-88889999 | kliniksehat@email.com',
    'target'    => 'Rp 100.000.000',
    'terkumpul' => 'Rp 90.000.000',
    'persen'    => '90',
    'donatur'   => '89',
    'sisa_hari' => '5'
  ],
  '3' => [
    'judul'     => 'Bantu Korban Banjir Jakarta',
    'kategori'  => 'Bencana Alam',
    'gambar'    => 'https://images.unsplash.com/photo-1544027993-37dbfe43562a?ixlib=rb-1.2.1&auto=format&fit=crop&w=1200&q=80',
    'yayasan'   => 'Relawan Cepat Tanggap',
    'deskripsi_yayasan' => 'Aksi cepat tanggap untuk bencana alam di seluruh Indonesia',
    'kontak'    => '021-77776666 | relawan@email.com',
    'target'    => 'Rp 200.000.000',
    'terkumpul' => 'Rp 120.000.000',
    'persen'    => '60',
    'donatur'   => '342',
    'sisa_hari' => '30'
  ]
];

$data = isset($database_kampanye[$id_kampanye]) ? $database_kampanye[$id_kampanye] : $database_kampanye['1'];
$actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Detail - <?php echo $data['judul']; ?></title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <style>
    :root {
      --primary-blue: #0d6efd;
      /* Biru Index */
      --secondary-blue: #3b82f6;
    }

    body {
      font-family: 'Poppins', sans-serif;
      background-color: #fcfcfc;
      color: #333;
    }

    /* Warna Brand Navbar */
    .navbar-brand span {
      color: var(--primary-blue);
    }

    .text-primary {
      color: var(--primary-blue) !important;
    }

    .img-campaign-container {
      border-radius: 20px;
      overflow: hidden;
      position: relative;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
    }

    .img-campaign-main {
      width: 100%;
      height: 450px;
      object-fit: cover;
    }

    .badge-category {
      position: absolute;
      top: 20px;
      left: 20px;
      background: rgba(255, 255, 255, 0.95);
      padding: 8px 20px;
      border-radius: 50px;
      font-size: 0.85rem;
      font-weight: 600;
      color: var(--primary-blue);
    }

    /* Tombol Donasi Biru Gradasi */
    .btn-donation {
      background: linear-gradient(90deg, #0d6efd 0%, #3b82f6 100%);
      border: none;
      color: white;
      font-weight: 600;
      padding: 15px;
      border-radius: 12px;
      transition: all 0.3s ease;
    }

    .btn-donation:hover {
      opacity: 0.9;
      color: white;
      transform: translateY(-3px);
      box-shadow: 0 10px 20px rgba(13, 110, 253, 0.2);
    }

    /* Progress Bar Biru */
    .progress-bar {
      background-color: var(--primary-blue) !important;
    }

    .nav-tabs-custom {
      border-bottom: none;
      background: #f1f3f5;
      padding: 5px;
      border-radius: 15px;
    }

    .nav-tabs-custom .nav-item {
      flex: 1;
      text-align: center;
    }

    .nav-tabs-custom .nav-link {
      border: none;
      color: #495057;
      font-weight: 500;
      padding: 10px;
      border-radius: 12px;
      transition: 0.3s;
    }

    .nav-tabs-custom .nav-link.active {
      color: var(--primary-blue);
      background-color: #fff;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
    }

    .share-icon-btn {
      width: 45px;
      height: 45px;
      display: flex;
      align-items: center;
      justify-content: center;
      border-radius: 10px;
      transition: all 0.2s;
      background: #f8f9fa;
      color: #555;
      border: 1px solid #eee;
    }

    .share-icon-btn:hover {
      background: var(--primary-blue);
      color: white;
      border-color: var(--primary-blue);
    }

    .contact-info-box {
      background-color: #f0f7ff;
      /* Biru sangat muda */
      border-radius: 15px;
      padding: 20px;
      border-left: 4px solid var(--primary-blue);
    }
  </style>
</head>

<body>

  <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
    <div class="container">
      <a class="navbar-brand d-flex align-items-center" href="index.php">
        <i class="fas fa-hands-helping fa-2x me-2 text-primary"></i>
        <div class="fw-bold fs-4">Sistem <span>Donasi</span></div>
      </a>
    </div>
  </nav>

  <main class="container py-5">
    <div class="row">

      <div class="col-lg-8">
        <nav aria-label="breadcrumb" class="mb-4">
          <a href="index.php" class="text-decoration-none text-muted small">
            <i class="fas fa-arrow-left me-1"></i> Kembali ke Beranda
          </a>
        </nav>

        <div class="img-campaign-container mb-4">
          <img src="<?php echo $data['gambar']; ?>" alt="Kampanye" class="img-campaign-main">
          <div class="badge-category shadow-sm"><?php echo $data['kategori']; ?></div>
        </div>

        <h1 class="fw-bold h2 mb-1"><?php echo $data['judul']; ?></h1>
        <p class="text-muted mb-4 small">Oleh <span class="text-primary fw-bold"><?php echo $data['yayasan']; ?></span></p>

        <ul class="nav nav-tabs nav-tabs-custom mt-4" id="campaignTab" role="tablist">
          <li class="nav-item">
            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#deskripsi">Deskripsi</button>
          </li>
          <li class="nav-item">
            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#penerima">Penerima Manfaat</button>
          </li>
          <li class="nav-item">
            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#donatur">Donatur</button>
          </li>
        </ul>

        <div class="tab-content py-4">
          <div class="tab-pane fade show active" id="deskripsi">
            <h5 class="fw-bold mb-3">Tentang Campaign Ini</h5>
            <p class="text-muted">
              Mari bantu sesama melalui program <strong><?php echo $data['judul']; ?></strong>.
              Setiap donasi yang Anda berikan akan sangat berarti bagi mereka yang membutuhkan.
            </p>
          </div>

          <div class="tab-pane fade" id="penerima">
            <h5 class="fw-bold mb-1"><?php echo $data['yayasan']; ?></h5>
            <p class="text-muted mb-4"><?php echo $data['deskripsi_yayasan']; ?></p>
            <div class="contact-info-box">
              <h6 class="text-primary small fw-bold text-uppercase mb-2">Informasi Kontak</h6>
              <p class="mb-0 fw-bold"><?php echo $data['kontak']; ?></p>
            </div>
          </div>

          <div class="tab-pane fade" id="donatur">
            <h5 class="fw-bold mb-3">Daftar Donatur (<?php echo $data['donatur']; ?>)</h5>
            <div class="list-group list-group-flush">
              <div class="list-group-item px-0 border-0 mb-3 d-flex align-items-center">
                <div class="bg-light rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                  <i class="fas fa-user text-primary"></i>
                </div>
                <div>
                  <h6 class="mb-0 fw-bold">Orang Baik</h6>
                  <small class="text-muted">Berdonasi sebesar Rp 100.000</small>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-4">
        <div style="position: sticky; top: 100px;">
          <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
            <div class="card-body p-4">
              <h6 class="text-muted mb-3 small fw-bold text-uppercase">Progress Donasi</h6>
              <h3 class="fw-bold text-primary mb-1"><?php echo $data['terkumpul']; ?></h3>

              <div class="d-flex justify-content-between align-items-center mb-2">
                <small class="text-muted">Target: <?php echo $data['target']; ?></small>
                <small class="fw-bold text-primary"><?php echo $data['persen']; ?>%</small>
              </div>

              <div class="progress mb-4" style="height: 10px; border-radius: 10px;">
                <div class="progress-bar progress-bar-striped progress-bar-animated"
                  style="width: <?php echo $data['persen']; ?>%"></div>
              </div>

              <div class="row text-center mb-4">
                <div class="col-6 border-end">
                  <h5 class="fw-bold mb-0"><?php echo $data['donatur']; ?></h5>
                  <small class="text-muted small">Donatur</small>
                </div>
                <div class="col-6">
                  <h5 class="fw-bold mb-0"><?php echo $data['sisa_hari']; ?></h5>
                  <small class="text-muted small">Hari Lagi</small>
                </div>
              </div>

              <a href="form-donasi.php?id=<?php echo $id_kampanye; ?>" class="btn btn-donation w-100 shadow-sm mb-2">
                <i class="fas fa-heart me-2"></i> Donasi Sekarang
              </a>
            </div>
          </div>

          <div class="card border-0 shadow-sm" style="border-radius: 15px;">
            <div class="card-body p-4 text-center">
              <h6 class="fw-bold mb-2">Bagikan Campaign</h6>
              <div class="d-flex justify-content-center gap-3 mt-3">
                <a href="https://api.whatsapp.com/send?text=<?php echo $actual_link; ?>" target="_blank" class="share-icon-btn text-decoration-none">
                  <i class="fab fa-whatsapp fa-lg"></i>
                </a>
                <button onclick="copyLink()" class="share-icon-btn border-0">
                  <i class="fas fa-link fa-lg"></i>
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    function copyLink() {
      const el = document.createElement('textarea');
      el.value = window.location.href;
      document.body.appendChild(el);
      el.select();
      document.execCommand('copy');
      document.body.removeChild(el);
      alert("Link kampanye berhasil disalin!");
    }
  </script>
</body>

</html>