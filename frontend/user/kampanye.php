<?php
require_once __DIR__ . '/../../backend/config/session.php';
require_once __DIR__ . '/../../backend/models/CampaignModel.php';
Session::start();

$campaignModel = new CampaignModel();
$campaigns = $campaignModel->getActiveCampaigns();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Semua Kampanye - Sistem Donasi</title>
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary: #10b981;
            --primary-dark: #0da271;
            --secondary: #3b82f6;
            --accent: #f59e0b;
            --light: #f8f9fa;
            --dark: #1f2937;
        }
        
        .campaign-card {
            transition: transform 0.3s ease;
        }
        
        .campaign-card:hover {
            transform: translateY(-5px);
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="index.php">
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
                        <a class="nav-link" href="index.php">
                            <i class="fas fa-home me-1"></i>Beranda
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="kampanye.php">
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

    <!-- Campaigns Section -->
    <section class="py-5">
        <div class="container">
            <div class="row mb-5">
                <div class="col-lg-8 mx-auto text-center">
                    <h2 class="fw-bold mb-3">Semua Kampanye</h2>
                    <p class="text-muted">Pilih kampanye yang ingin Anda dukung</p>
                </div>
            </div>
            
            <div class="row">
                <?php if (empty($campaigns)): ?>
                    <div class="col-12 text-center">
                        <p class="text-muted">Belum ada kampanye aktif saat ini.</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($campaigns as $campaign): ?>
                        <?php
                        $badgeClass = 'bg-primary';
                        switch (strtolower($campaign['kategori'] ?? 'umum')) {
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
                        $progress = min(100, round(($campaign['dana_terkumpul'] / $campaign['target_dana']) * 100));
                        $daysLeft = max(0, (strtotime($campaign['tgl_selesai']) - time()) / (60*60*24));
                        ?>
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="card campaign-card h-100 shadow-sm">
                                <div class="position-relative">
                                    <img src="<?php echo htmlspecialchars($campaign['gambar'] ?? 'https://images.unsplash.com/photo-1593113630400-ea4288922497?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80'); ?>" 
                                         class="card-img-top" alt="<?php echo htmlspecialchars($campaign['judul_kampanye']); ?>" style="height: 200px; object-fit: cover;">
                                    <span class="position-absolute top-0 start-0 m-3 badge <?php echo $badgeClass; ?>"><?php echo htmlspecialchars($campaign['kategori'] ?? 'Umum'); ?></span>
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
                                        <small><i class="fas fa-users me-1"></i> 0 donatur</small>
                                        <small><i class="fas fa-clock me-1"></i> <?php echo ceil($daysLeft); ?> hari lagi</small>
                                    </div>
                                    
                                    <a href="detail.php?id=<?php echo $campaign['id_kampanye']; ?>" class="btn btn-primary w-100">
                                        <i class="fas fa-heart me-1"></i>Donasi Sekarang
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>