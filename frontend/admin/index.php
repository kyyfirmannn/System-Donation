<?php include('header.php'); ?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-0">Dashboard Overview</h4>
            <small class="text-muted">Ringkasan performa sistem donasi</small>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card p-3 stat-card">
                <h6>Total Donasi Terkumpul</h6>
                <h4>Rp 1.225.000.000</h4>
                <small class="text-success"><i class="bi bi-arrow-up"></i> +12.5% dari bulan lalu</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 stat-card">
                <h6>Total Donor</h6>
                <h4>282</h4>
                <small class="text-primary">+8 donor bulan ini</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 stat-card">
                <h6>Campaign Aktif</h6>
                <h4>5</h4>
                <small class="text-muted">dari 6 total campaign</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 stat-card">
                <h6>Rata-rata Donasi</h6>
                <h4>Rp 3.450.000</h4>
                <small class="text-muted">9 donasi berhasil</small>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-8">
            <div class="card p-3 h-100">
                <h6 class="fw-bold mb-3">Tren Donasi Bulanan</h6>
                <canvas id="lineChart" height="200"></canvas>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card p-3 h-100 text-center">
                <h6 class="fw-bold mb-3 text-start">Distribusi per Kategori</h6>
                <canvas id="pieChart"></canvas>
            </div>
        </div>
    </div>

    <div class="card p-3">
        <h6 class="fw-bold mb-3">Campaign Terbaru</h6>
        <div class="list-group list-group-flush">
            <?php
            // Simulasi data dari PHP
            $campaigns = [
                ['nama' => 'Bantu 1000 Anak Sekolah', 'target' => 500000000, 'terkumpul' => 350000000, 'color' => 'bg-primary'],
                ['nama' => 'Operasi Medis untuk Anak', 'target' => 300000000, 'terkumpul' => 180000000, 'color' => 'bg-info'],
            ];

            foreach ($campaigns as $c) {
                $persen = ($c['terkumpul'] / $c['target']) * 100;
                echo "
                <div class='mb-3'>
                    <div class='d-flex justify-content-between mb-1'>
                        <span class='small fw-bold'>{$c['nama']}</span>
                        <span class='small text-muted'>{$persen}%</span>
                    </div>
                    <div class='progress' style='height: 8px;'>
                        <div class='progress-bar {$c['color']}' style='width: {$persen}%'></div>
                    </div>
                </div>";
            }
            ?>
        </div>
    </div>
</main>

<script>
    const ctxLine = document.getElementById('lineChart').getContext('2d');
    new Chart(ctxLine, {
        type: 'line',
        data: {
            labels: ['Juli', 'Agu', 'Sep', 'Okt', 'Nov', 'Des', 'Jan'],
            datasets: [{
                label: 'Total Terkumpul',
                data: [100000000, 450000000, 420000000, 600000000, 550000000, 750000000, 300000000],
                borderColor: '#7d44fd',
                tension: 0.4,
                fill: true,
                backgroundColor: 'rgba(125, 68, 253, 0.1)'
            }]
        }
    });

    const ctxPie = document.getElementById('pieChart').getContext('2d');
    new Chart(ctxPie, {
        type: 'doughnut',
        data: {
            labels: ['Kesehatan', 'Pendidikan', 'Sosial', 'Lingkungan'],
            datasets: [{
                data: [25, 30, 35, 10],
                backgroundColor: ['#7d44fd', '#0dcaf0', '#ffc107', '#198754']
            }]
        }
    });
</script>
