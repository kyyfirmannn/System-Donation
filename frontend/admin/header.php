<?php
// Deteksi halaman aktif
$currentPage = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DonasiKu - Admin Panel</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <style>
        body { background-color: #f8f9fa; font-family: 'Inter', sans-serif; }
        .sidebar {
            min-height: 100vh;
            background: white;
            border-right: 1px solid #dee2e6;
            width: 240px;
            position: fixed;
        }
        .nav-link {
            color: #6c757d;
            padding: 10px 20px;
            font-weight: 500;
        }
        .nav-link.active {
            background-color: #e7f1ff;
            color: #0d6efd;
            border-radius: 8px;
        }
        .main-content {
            margin-left: 240px;
            padding: 30px;
        }
        .card { border: none; border-radius: 12px; }
    </style>
</head>
<body>

<div class="sidebar d-flex flex-column p-3">
    <!-- Logo -->
    <div class="d-flex align-items-center mb-4 px-2">
        <i class="bi bi-heart-fill text-primary me-2 fs-4"></i>
        <span class="fw-bold fs-5">DonasiKu</span>
    </div>

    <!-- Menu -->
    <ul class="nav nav-pills flex-column mb-auto">

        <li class="nav-item">
            <a href="dashboard.php"
               class="nav-link <?= ($currentPage == 'dashboard.php') ? 'active' : '' ?>">
                <i class="bi bi-speedometer2 me-2"></i> Dashboard
            </a>
        </li>

        <li class="nav-item">
            <a href="campaign.php"
               class="nav-link <?= ($currentPage == 'campaign.php') ? 'active' : '' ?>">
                <i class="bi bi-megaphone me-2"></i> Campaign
            </a>
        </li>

        <li class="nav-item">
            <a href="donor.php"
               class="nav-link <?= ($currentPage == 'donor.php') ? 'active' : '' ?>">
                <i class="bi bi-people me-2"></i> Donor
            </a>
        </li>

        <li class="nav-item">
            <a href="kategori.php"
               class="nav-link <?= ($currentPage == 'kategori.php') ? 'active' : '' ?>">
                <i class="bi bi-grid me-2"></i> Kategori
            </a>
        </li>

        <li class="nav-item">
            <a href="penerima.php"
               class="nav-link <?= ($currentPage == 'penerima.php') ? 'active' : '' ?>">
                <i class="bi bi-grid me-2"></i> Penerima Manfaat
            </a>
        </li>

    </ul>

    <hr>

    <a href="../user/index.php" class="btn btn-light btn-sm w-100 mt-auto">
        <i class="bi bi-arrow-left"></i> Ke Halaman Publik
    </a>
</div>

<div class="main-content">
