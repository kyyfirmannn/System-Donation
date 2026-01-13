<?php
require_once __DIR__ . '/../../backend/config/session.php';
require_once __DIR__ . '/../../backend/models/CampaignModel.php';
require_once __DIR__ . '/../../backend/models/UserModel.php';
require_once __DIR__ . '/../../backend/models/DonationModel.php';
Session::start();

$campaignModel = new CampaignModel();
$campaigns = $campaignModel->getCampaignsForDisplay(3);
$userModel = new UserModel();
$donationModel = new DonationModel();
$stats = $donationModel->getStatistics();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Donasi - Berbagi Kebaikan</title>
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
    
    <style>
        :root {
            --primary: #10b981;
            --primary-dark: #0da271;
            --secondary: #3b82f6;
            --accent: #f59e0b;
            --light: #f8f9fa;
            --dark: #1f2937;
        }
        
        .hero-section {
            background: var(--secondary);
            padding: 80px 0;
        }
        
        .campaign-card {
            transition: transform 0.3s ease;
        }
        
        .campaign-card:hover {
            transform: translateY(-5px);
        }
        
        .stat-card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="index.html">
                <i class="fas fa-hands-helping fa-2x me-2 text-primary"></i>
                <div>
                    <span class="fw-bold fs-4">Sistem</span>
                    <span class="fw-bold fs-4 text-primary">Donasi</span>
                </div>
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item">
                        <a class="nav-link active" href="index.php">
                            <i class="fas fa-home me-1"></i>Beranda
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="kampanye.php">
                            <i class="fas fa-heart me-1"></i>Kampanye
                        </a>
                    </li>
                    <?php if (Session::isLoggedIn()): ?>
                        <li class="nav-item">
                            <span class="nav-link">
                                <i class="fas fa-user me-1"></i><?php echo htmlspecialchars(Session::get('user_name')); ?>
                            </span>
                        </li>
                        <li class="nav-item ms-2">
                            <a class="btn btn-outline-danger" href="logout.php">
                                <i class="fas fa-sign-out-alt me-1"></i>Logout
                            </a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="donatur.php">
                                <i class="fas fa-sign-in-alt me-1"></i>Login
                            </a>
                        </li>
                        <li class="nav-item ms-2">
                            <a class="btn btn-primary" href="daftar.php">
                                <i class="fas fa-user-plus me-1"></i>Daftar
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section text-white">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="display-4 fw-bold mb-4">Berbagi Kebaikan, Wujudkan Harapan</h1>
                    <p class="lead mb-4">Bergabunglah dengan ribuan orang baik untuk membantu mereka yang membutuhkan melalui sistem donasi yang aman dan transparan.</p>
                    <div class="d-flex flex-wrap gap-3">
                        <a href="detail.php?id=1" class="btn btn-light btn-lg">
                            <i class="fas fa-heart me-2"></i>Donasi Sekarang
                        </a>
                        <a href="#cara-kerja" class="btn btn-outline-light btn-lg">
                            <i class="fas fa-info-circle me-2"></i>Cara Kerja
                        </a>
                    </div>
                </div>
                <div class="col-lg-6 mt-4 mt-lg-0">
                    <img src="https://images.unsplash.com/photo-1593113630400-ea4288922497?ixlib=rb-1.2.1&auto=format&fit=crop&w=1000&q=80" 
                         alt="Hero Image" class="img-fluid rounded shadow-lg">
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row text-center">
                <div class="col-md-4 col-6 mb-4">
                    <div class="stat-card p-4 bg-white">
                        <i class="fas fa-hand-holding-heart fa-3x text-primary mb-3"></i>
                        <h3 class="fw-bold"><?php echo $campaignModel->countCampaigns('aktif'); ?></h3>
                        <p class="text-muted">Kampanye Aktif</p>
                    </div>
                </div>
                <div class="col-md-4 col-6 mb-4">
                    <div class="stat-card p-4 bg-white">
                        <i class="fas fa-users fa-3x text-secondary mb-3"></i>
                        <h3 class="fw-bold"><?php echo $userModel->countDonors(); ?></h3>
                        <p class="text-muted">Donatur Bergabung</p>
                    </div>
                </div>
                <div class="col-md-4 col-6 mb-4">
                    <div class="stat-card p-4 bg-white">
                        <i class="fas fa-money-bill-wave fa-3x text-success mb-3"></i>
                        <h3 class="fw-bold"><?php 
                            $total = $stats['total_donations'] ?? 0;
                            if ($total >= 1000000000) {
                                echo 'Rp ' . number_format($total / 1000000000, 1) . 'M';
                            } elseif ($total >= 1000000) {
                                echo 'Rp ' . number_format($total / 1000000, 1) . 'Jt';
                            } else {
                                echo 'Rp ' . number_format($total, 0, ',', '.');
                            }
                        ?></h3>
                        <p class="text-muted">Terkumpul</p>
                    </div>
                </div>
                <!-- <div class="col-md-3 col-6 mb-4">
                    <div class="stat-card p-4 bg-white">
                        <i class="fas fa-smile fa-3x text-warning mb-3"></i>
                        <h3 class="fw-bold">156</h3>
                        <p class="text-muted">Penerima Tertolong</p>
                    </div>
                </div> -->
            </div>
        </div>
    </section>

    <!-- Popular Campaigns -->
    <section class="py-5">
        <div class="container">
            <div class="row mb-5">
                <div class="col-lg-8 mx-auto text-center">
                    <h2 class="fw-bold mb-3">Kampanye Populer</h2>
                    <p class="text-muted">Pilih kampanye yang ingin Anda dukung</p>
                </div>
            </div>
            
            <div class="row">
                <?php foreach ($campaigns as $campaign): ?>
                    <?php
                    $badgeClass = 'bg-primary';
                    switch (strtolower($campaign['kategori'])) {
                        case 'pendidikan':
                            $badgeClass = 'bg-success';
                            break;
                        case 'kesehatan':
                            $badgeClass = 'bg-danger';
                            break;
                        case 'bencana alam':
                            $badgeClass = 'bg-warning';
                            break;
                        case 'lingkungan':
                            $badgeClass = 'bg-info';
                            break;
                    }
                    $progress = min(100, round($campaign['progress']));
                    $daysLeft = max(0, $campaign['days_left']);
                    ?>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card campaign-card h-100 shadow-sm">
                            <div class="position-relative">
                                <img src="<?php echo htmlspecialchars($campaign['gambar']); ?>" 
                                     class="card-img-top" alt="<?php echo htmlspecialchars($campaign['judul_kampanye']); ?>" style="height: 200px; object-fit: cover;">
                                <span class="position-absolute top-0 start-0 m-3 badge <?php echo $badgeClass; ?>"><?php echo htmlspecialchars($campaign['kategori']); ?></span>
                            </div>
                            <div class="card-body">
                                <h5 class="card-title fw-bold"><?php echo htmlspecialchars($campaign['judul_kampanye']); ?></h5>
                                <p class="card-text text-muted"><?php echo htmlspecialchars(substr($campaign['deskripsi'], 0, 100)) . (strlen($campaign['deskripsi']) > 100 ? '...' : ''); ?></p>
                                
                                <div class="progress mb-3" style="height: 8px;">
                                    <div class="progress-bar <?php echo $badgeClass; ?>" style="width: <?php echo $progress; ?>%"></div>
                                </div>
                                
                                <div class="d-flex justify-content-between mb-2">
                                    <small>Terkumpul:</small>
                                    <strong>Rp <?php echo number_format($campaign['dana_terkumpul'], 0, ',', '.'); ?></strong>
                                </div>
                                <div class="d-flex justify-content-between mb-3">
                                    <small>Target:</small>
                                    <strong>Rp <?php echo number_format($campaign['target_dana'], 0, ',', '.'); ?></strong>
                                </div>
                                
                                <div class="d-flex justify-content-between mb-4">
                                    <small><i class="fas fa-users me-1"></i> <?php echo $campaign['donor_count']; ?> donatur</small>
                                    <small><i class="fas fa-clock me-1"></i> <?php echo $daysLeft; ?> hari lagi</small>
                                </div>
                                
                                <a href="detail.php?id=<?php echo $campaign['id_kampanye']; ?>" class="btn btn-primary w-100">
                                    <i class="fas fa-heart me-1"></i>Donasi Sekarang
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <div class="text-center mt-4">
                <a href="kampanye.php" class="btn btn-outline-primary btn-lg">
                    <i class="fas fa-list me-2"></i>Lihat Semua Kampanye
                </a>
            </div>
        </div>
    </section>

    <!-- How It Works -->
    <section class="py-5 bg-light" id="cara-kerja">
        <div class="container">
            <div class="row mb-5">
                <div class="col-lg-8 mx-auto text-center">
                    <h2 class="fw-bold mb-3">Cara Berdonasi</h2>
                    <p class="text-muted">Hanya dalam 4 langkah mudah</p>
                </div>
            </div>
            
            <div class="row">
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="text-center p-4">
                        <div class="step-number mb-3 mx-auto rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" 
                             style="width: 70px; height: 70px;">
                            <i class="fas fa-user-plus fa-2x"></i>
                        </div>
                        <h5 class="fw-bold mb-2">1. Daftar Akun</h5>
                        <p class="text-muted">Registrasi akun donatur dengan data diri yang valid</p>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="text-center p-4">
                        <div class="step-number mb-3 mx-auto rounded-circle bg-success text-white d-flex align-items-center justify-content-center" 
                             style="width: 70px; height: 70px;">
                            <i class="fas fa-search fa-2x"></i>
                        </div>
                        <h5 class="fw-bold mb-2">2. Pilih Kampanye</h5>
                        <p class="text-muted">Temukan kampanye yang sesuai dengan passion Anda</p>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="text-center p-4">
                        <div class="step-number mb-3 mx-auto rounded-circle bg-warning text-white d-flex align-items-center justify-content-center" 
                             style="width: 70px; height: 70px;">
                            <i class="fas fa-donate fa-2x"></i>
                        </div>
                        <h5 class="fw-bold mb-2">3. Lakukan Donasi</h5>
                        <p class="text-muted">Tentukan jumlah dan metode pembayaran yang nyaman</p>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="text-center p-4">
                        <div class="step-number mb-3 mx-auto rounded-circle bg-info text-white d-flex align-items-center justify-content-center" 
                             style="width: 70px; height: 70px;">
                            <i class="fas fa-check-circle fa-2x"></i>
                        </div>
                        <h5 class="fw-bold mb-2">4. Dapatkan Laporan</h5>
                        <p class="text-muted">Pantau perkembangan dan laporan penggunaan dana</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <h4 class="fw-bold mb-3">
                        <i class="fas fa-hands-helping me-2"></i>Sistem Donasi
                    </h4>
                    <p class="text-muted">
                        Platform donasi online yang aman, transparan, dan terpercaya untuk membantu mereka yang membutuhkan.
                    </p>
                    <div class="social-links mt-3">
                        <a href="#" class="text-white me-3"><i class="fab fa-facebook fa-lg"></i></a>
                        <a href="#" class="text-white me-3"><i class="fab fa-twitter fa-lg"></i></a>
                        <a href="#" class="text-white me-3"><i class="fab fa-instagram fa-lg"></i></a>
                        <a href="#" class="text-white"><i class="fab fa-youtube fa-lg"></i></a>
                    </div>
                </div>
                
                <div class="col-lg-2 col-md-6 mb-4">
                    <h5 class="fw-bold mb-3">Tautan Cepat</h5>
                    <ul class="list-unstyled">
                        <?php if (Session::isLoggedIn()): ?>
                            <li class="mb-2"><span class="text-muted">Selamat datang, <?php echo htmlspecialchars(Session::get('user_name')); ?></span></li>
                            <li class="mb-2"><a href="logout.php" class="text-muted text-decoration-none">Logout</a></li>
                        <?php else: ?>
                            <li class="mb-2"><a href="donatur.php" class="text-muted text-decoration-none">Login</a></li>
                            <li class="mb-2"><a href="daftar.php" class="text-muted text-decoration-none">Daftar</a></li>
                        <?php endif; ?>
                    </ul>
                </div>
                
                <div class="col-lg-3 col-md-6 mb-4">
                    <h5 class="fw-bold mb-3">Kontak Kami</h5>
                    <ul class="list-unstyled">
                        <li class="mb-3">
                            <i class="fas fa-map-marker-alt me-2"></i>
                            <span class="text-muted">Jl. Contoh No. 123, Jakarta</span>
                        </li>
                        <li class="mb-3">
                            <i class="fas fa-phone me-2"></i>
                            <span class="text-muted">(021) 1234-5678</span>
                        </li>
                        <li class="mb-3">
                            <i class="fas fa-envelope me-2"></i>
                            <span class="text-muted">info@sistemdonasi.com</span>
                        </li>
                    </ul>
                </div>
                
                <div class="col-lg-3 mb-4">
                    <h5 class="fw-bold mb-3">Newsletter</h5>
                    <p class="text-muted mb-3">Dapatkan info kampanye terbaru langsung di email</p>
                    <form class="input-group">
                        <input type="email" class="form-control" placeholder="Email Anda">
                        <button class="btn btn-primary" type="submit">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </form>
                </div>
            </div>
            
            <hr class="bg-secondary">
            
            <div class="row">
                <div class="col-md-6">
                    <p class="text-muted mb-0">
                        &copy; 2024 Sistem Donasi. All rights reserved.
                    </p>
                </div>
                <div class="col-md-6 text-md-end">
                    <a href="#" class="text-muted text-decoration-none me-3">Privacy Policy</a>
                    <a href="#" class="text-muted text-decoration-none">Terms of Service</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JS -->
    <script src="assets/js/main.js"></script>
    
    <script>
        // Smooth scroll untuk anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const targetId = this.getAttribute('href');
                if (targetId === '#') return;
                
                const targetElement = document.querySelector(targetId);
                if (targetElement) {
                    window.scrollTo({
                        top: targetElement.offsetTop - 80,
                        behavior: 'smooth'
                    });
                }
            });
        });
    </script>
</body>
</html>