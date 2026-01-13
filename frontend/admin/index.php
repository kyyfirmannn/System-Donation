<?php
// frontend/admin/index.php atau frontend/admin/dashboard.php
require_once '../../backend/config/database.php';
require_once '../../backend/models/DonationModel.php';
require_once '../../backend/models/CampaignModel.php';
require_once '../../backend/models/UserModel.php';
?>

<?php include('header.php'); ?>

<?php
// Helper function untuk format waktu
function timeAgo($datetime) {
    $now = new DateTime();
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    if ($diff->y > 0) {
        return $diff->y . ' tahun lalu';
    } elseif ($diff->m > 0) {
        return $diff->m . ' bulan lalu';
    } elseif ($diff->d > 0) {
        return $diff->d . ' hari lalu';
    } elseif ($diff->h > 0) {
        return $diff->h . ' jam lalu';
    } elseif ($diff->i > 0) {
        return $diff->i . ' menit lalu';
    } else {
        return 'Baru saja';
    }
}
?>

<main class="col-md-12 ms-sm-auto col-lg-12 px-md-4 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-0">Dashboard Overview</h4>
            <small class="text-muted">Ringkasan performa sistem donasi</small>
        </div>
        <div class="text-end">
            <span class="badge bg-light text-dark"><?php echo date('d F Y'); ?></span>
        </div>
    </div>

    <?php
    // Inisialisasi model
    $donationModel = new DonationModel();
    $campaignModel = new CampaignModel();
    $userModel = new UserModel();

    // Ambil data statistik
    $stats = $donationModel->getStatistics();
    $totalDonations = $stats['total_donations'] ?? 0;
    $totalDonors = $stats['total_donors'] ?? 0;
    $activeCampaigns = $campaignModel->countCampaigns('aktif');
    $totalCampaigns = $campaignModel->countCampaigns();
    
    // Hitung rata-rata donasi
    $avgDonation = 0;
    if ($stats['total_donations'] > 0 && $donationModel->countDonations('berhasil') > 0) {
        $avgDonation = $stats['total_donations'] / $donationModel->countDonations('berhasil');
    }

    // Data tren bulanan
    $monthlyData = $stats['monthly'] ?? [];
    $monthlyLabels = [];
    $monthlyAmounts = [];
    
    foreach ($monthlyData as $month) {
        $monthlyLabels[] = date('M', strtotime($month['month'] . '-01'));
        $monthlyAmounts[] = $month['total'];
    }

    // Data distribusi per kampanye (top 4)
    $topCampaigns = $campaignModel->getTopCampaigns(4);
    $distribusiLabels = [];
    $distribusiAmounts = [];
    $distribusiColors = ['#7d44fd', '#0dcaf0', '#ffc107', '#198754'];
    
    foreach ($topCampaigns as $index => $campaign) {
        $distribusiLabels[] = substr($campaign['judul_kampanye'], 0, 20) . '...';
        $distribusiAmounts[] = $campaign['dana_terkumpul'];
    }

    // Data real untuk dashboard
    $donationGrowth = $donationModel->getDonationGrowthPercentage();
    $donorsThisMonth = $donationModel->countDonorsThisMonth();
    $paymentMethods = $donationModel->getPaymentMethodStatistics();

    // Format Rupiah
    function formatRupiah($angka) {
        if ($angka >= 1000000000) {
            return 'Rp ' . number_format($angka / 1000000000, 1, ',', '.') . 'M';
        } elseif ($angka >= 1000000) {
            return 'Rp ' . number_format($angka / 1000000, 1, ',', '.') . 'jt';
        } elseif ($angka >= 1000) {
            return 'Rp ' . number_format($angka / 1000, 1, ',', '.') . 'rb';
        }
        return 'Rp ' . number_format($angka, 0, ',', '.');
    }
    ?>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card p-3 stat-card">
                <h6 class="text-muted small mb-2">Total Donasi Terkumpul</h6>
                <h4 class="fw-bold mb-2"><?php echo formatRupiah($totalDonations); ?></h4>
                <small class="text-success">
                    <i class="bi bi-arrow-up"></i> 
                    <?php echo $donationGrowth; ?>% dari bulan lalu
                </small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 stat-card">
                <h6 class="text-muted small mb-2">Total Donor</h6>
                <h4 class="fw-bold mb-2"><?php echo $totalDonors; ?></h4>
                <small class="text-primary">
                    <?php echo $donorsThisMonth; ?> donor bulan ini
                </small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 stat-card">
                <h6 class="text-muted small mb-2">Campaign Aktif</h6>
                <h4 class="fw-bold mb-2"><?php echo $activeCampaigns; ?></h4>
                <small class="text-muted">dari <?php echo $totalCampaigns; ?> total campaign</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 stat-card">
                <h6 class="text-muted small mb-2">Rata-rata Donasi</h6>
                <h4 class="fw-bold mb-2"><?php echo formatRupiah($avgDonation); ?></h4>
                <small class="text-muted"><?php echo $donationModel->countDonations('berhasil'); ?> donasi berhasil</small>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-8">
            <div class="card p-3 h-100">
                <h6 class="fw-bold mb-3">Tren Donasi 6 Bulan Terakhir</h6>
                <canvas id="lineChart" height="200"></canvas>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card p-3 h-100 text-center">
                <h6 class="fw-bold mb-3 text-start">Top 4 Campaign</h6>
                <canvas id="pieChart"></canvas>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card p-3 mb-3">
                <h6 class="fw-bold mb-3">Campaign dengan Donasi Terbanyak</h6>
                <div class="list-group list-group-flush">
                    <?php
                    $topCampaigns = $campaignModel->getTopCampaigns(4);
                    $progressColors = ['bg-primary', 'bg-info', 'bg-warning', 'bg-success'];
                    
                    foreach ($topCampaigns as $index => $campaign) {
                        $persen = ($campaign['dana_terkumpul'] / $campaign['target_dana']) * 100;
                        $persen = min(100, $persen); // Maksimal 100%
                        $colorClass = $progressColors[$index] ?? 'bg-primary';
                        ?>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span class="small fw-bold"><?php echo $campaign['judul_kampanye']; ?></span>
                                <span class="small text-muted"><?php echo number_format($persen, 1); ?>%</span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar <?php echo $colorClass; ?>" style="width: <?php echo $persen; ?>%"></div>
                            </div>
                            <div class="d-flex justify-content-between mt-1">
                                <small class="text-muted">
                                    Rp <?php echo number_format($campaign['dana_terkumpul'], 0, ',', '.'); ?> terkumpul
                                </small>
                                <small class="text-muted">
                                    Target: Rp <?php echo number_format($campaign['target_dana'], 0, ',', '.'); ?>
                                </small>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card p-3">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold mb-0">Donasi Terbaru</h6>
                    <a href="donor.php" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-sm table-hover">
                        <thead>
                            <tr>
                                <th>Donatur</th>
                                <th>Jumlah</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $recentDonations = $donationModel->getAllDonations(5);
                            
                            if (empty($recentDonations)) {
                                echo '<tr><td colspan="4" class="text-center text-muted">Belum ada donasi</td></tr>';
                            } else {
                                foreach ($recentDonations as $donation) {
                                    $statusClass = '';
                                    if ($donation['status'] == 'berhasil') {
                                        $statusClass = 'badge bg-success';
                                    } elseif ($donation['status'] == 'pending') {
                                        $statusClass = 'badge bg-warning';
                                    } else {
                                        $statusClass = 'badge bg-danger';
                                    }
                                    ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="rounded-circle bg-light d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; margin-right: 10px;">
                                                    <i class="bi bi-person text-primary"></i>
                                                </div>
                                                <span class="small"><?php echo $donation['nama_pengguna']; ?></span>
                                            </div>
                                        </td>
                                        <td class="fw-bold"><?php echo formatRupiah($donation['jumlah_donasi']); ?></td>
                                        <td>
                                            <span class="<?php echo $statusClass; ?> badge-sm">
                                                <?php echo ucfirst($donation['status']); ?>
                                            </span>
                                        </td>
                                        <td class="text-muted small">
                                            <?php echo date('d/m/Y', strtotime($donation['tgl_donasi'])); ?>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card p-3">
                <h6 class="fw-bold mb-3">Statistik Pembayaran</h6>
                <div class="row text-center">
                    <div class="col-4">
                        <div class="p-2">
                            <h4 class="fw-bold text-primary mb-1"><?php echo $donationModel->countDonations('berhasil'); ?></h4>
                            <small class="text-muted">Berhasil</small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="p-2">
                            <h4 class="fw-bold text-warning mb-1"><?php echo $donationModel->countDonations('pending'); ?></h4>
                            <small class="text-muted">Pending</small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="p-2">
                            <h4 class="fw-bold text-danger mb-1"><?php echo $donationModel->countDonations('gagal'); ?></h4>
                            <small class="text-muted">Gagal</small>
                        </div>
                    </div>
                </div>
                <div class="mt-3">
                    <?php
                    $progressColors = ['bg-primary', 'bg-info', 'bg-success', 'bg-warning', 'bg-danger'];
                    foreach ($paymentMethods as $index => $method) {
                        $colorClass = $progressColors[$index % count($progressColors)];
                        ?>
                        <div class="d-flex justify-content-between mb-1">
                            <span class="small"><?php echo ucfirst($method['metode_pembayaran']); ?></span>
                            <span class="small"><?php echo $method['percentage']; ?>%</span>
                        </div>
                        <div class="progress mb-2" style="height: 6px;">
                            <div class="progress-bar <?php echo $colorClass; ?>" style="width: <?php echo $method['percentage']; ?>%"></div>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card p-3">
                <h6 class="fw-bold mb-3">Aktivitas Terbaru</h6>
                <div class="timeline">
                    <?php
                    // Ambil aktivitas terbaru dari database
                    $recentActivities = $donationModel->getRecentActivities(2);
                    $recentCampaigns = $campaignModel->getRecentCampaigns(2);
                    
                    // Gabungkan dan sort berdasarkan tanggal
                    $allActivities = [];
                    
                    foreach ($recentActivities as $activity) {
                        $allActivities[] = [
                            'icon' => 'bi-cash-coin',
                            'color' => 'primary',
                            'text' => $activity['text'],
                            'time' => timeAgo($activity['date']),
                            'date' => $activity['date']
                        ];
                    }
                    
                    foreach ($recentCampaigns as $campaign) {
                        $allActivities[] = [
                            'icon' => 'bi-check-circle',
                            'color' => 'info',
                            'text' => 'Kampanye baru "' . $campaign['judul_kampanye'] . '" dipublikasi',
                            'time' => timeAgo($campaign['dibuat_pada']),
                            'date' => $campaign['dibuat_pada']
                        ];
                    }
                    
                    // Sort by date descending
                    usort($allActivities, function($a, $b) {
                        return strtotime($b['date']) - strtotime($a['date']);
                    });
                    
                    // Take top 4
                    $allActivities = array_slice($allActivities, 0, 4);
                    
                    foreach ($allActivities as $activity) {
                        ?>
                        <div class="d-flex mb-3">
                            <div class="rounded-circle bg-<?php echo $activity['color']; ?> bg-opacity-10 d-flex align-items-center justify-content-center" 
                                 style="width: 40px; height: 40px; margin-right: 15px;">
                                <i class="bi <?php echo $activity['icon']; ?> text-<?php echo $activity['color']; ?>"></i>
                            </div>
                            <div class="flex-grow-1">
                                <p class="mb-1 small"><?php echo $activity['text']; ?></p>
                                <small class="text-muted"><?php echo $activity['time']; ?></small>
                            </div>
                        </div>
                        <?php
                    }
                    
                    if (empty($allActivities)) {
                        echo '<p class="text-muted small">Belum ada aktivitas terbaru</p>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Data dari PHP untuk chart
    const monthlyLabels = <?php echo json_encode($monthlyLabels); ?>;
    const monthlyAmounts = <?php echo json_encode($monthlyAmounts); ?>;
    
    const distribusiLabels = <?php echo json_encode($distribusiLabels); ?>;
    const distribusiAmounts = <?php echo json_encode($distribusiAmounts); ?>;
    const distribusiColors = <?php echo json_encode($distribusiColors); ?>;

    // Line Chart - Tren Donasi
    const ctxLine = document.getElementById('lineChart').getContext('2d');
    new Chart(ctxLine, {
        type: 'line',
        data: {
            labels: monthlyLabels,
            datasets: [{
                label: 'Total Terkumpul',
                data: monthlyAmounts,
                borderColor: '#7d44fd',
                tension: 0.4,
                fill: true,
                backgroundColor: 'rgba(125, 68, 253, 0.1)',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            if (value >= 1000000) {
                                return 'Rp ' + (value / 1000000).toFixed(1) + 'jt';
                            } else if (value >= 1000) {
                                return 'Rp ' + (value / 1000).toFixed(1) + 'rb';
                            }
                            return 'Rp ' + value;
                        }
                    }
                }
            }
        }
    });

    // Doughnut Chart - Distribusi Campaign
    const ctxPie = document.getElementById('pieChart').getContext('2d');
    new Chart(ctxPie, {
        type: 'doughnut',
        data: {
            labels: distribusiLabels,
            datasets: [{
                data: distribusiAmounts,
                backgroundColor: distribusiColors,
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 15,
                        boxWidth: 12,
                        font: {
                            size: 10
                        }
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.label || '';
                            let value = context.parsed;
                            return label + ': Rp ' + value.toLocaleString('id-ID');
                        }
                    }
                }
            },
            cutout: '65%'
        }
    });
</script>

<?php
// Update header.php untuk menampilkan data real-time
?>