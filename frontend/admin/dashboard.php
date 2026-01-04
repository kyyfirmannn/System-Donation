<?php include 'header.php'; ?>

<div class="header-content mb-4">
    <h2 class="fw-bold mb-0">Dashboard Overview</h2>
    <p class="text-muted">Ringkasan performa sistem donasi</p>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card shadow-sm p-3">
            <small class="text-muted d-block mb-1">Total Donasi Terkumpul</small>
            <h4 class="fw-bold mb-1">Rp 1.225.000.000</h4>
            <span class="text-success small fw-medium"><i class="bi bi-arrow-up-right"></i> 12.5% <span class="text-muted">dari bulan lalu</span></span>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm p-3">
            <small class="text-muted d-block mb-1">Total Donor</small>
            <h4 class="fw-bold mb-1">8</h4>
            <span class="text-success small fw-medium"><i class="bi bi-arrow-up-right"></i> +8 donor <span class="text-muted">bulan ini</span></span>
        </div>
    </div>
    </div>

<div class="row mb-4">
    <div class="col-md-8">
        <div class="card shadow-sm p-4">
            <h6 class="fw-bold mb-4">Tren Donasi Bulanan</h6>
            <canvas id="lineChart" style="height: 300px;"></canvas>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm p-4">
            <h6 class="fw-bold mb-4">Distribusi per Kategori</h6>
            <canvas id="pieChart"></canvas>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Contoh Chart Garis
    const ctxLine = document.getElementById('lineChart');
    new Chart(ctxLine, {
        type: 'line',
        data: {
            labels: ['Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des', 'Jan'],
            datasets: [{
                label: 'Donasi',
                data: [3000000, 4800000, 4300000, 5800000, 5000000, 6800000, 1500000],
                borderColor: '#0d6efd',
                tension: 0.4,
                fill: true,
                backgroundColor: 'rgba(13, 110, 253, 0.05)'
            }]
        }
    });

    // Contoh Chart Pie
    const ctxPie = document.getElementById('pieChart');
    new Chart(ctxPie, {
        type: 'pie',
        data: {
            labels: ['Bencana Alam', 'Kesehatan', 'Pendidikan'],
            datasets: [{
                data: [46, 21, 9],
                backgroundColor: ['#ff6384', '#36a2eb', '#4bc0c0']
            }]
        }
    });
</script>

<?php // Tutup div main-content dari header.php ?>
</div> 
</body>
</html>