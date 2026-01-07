-- Schema database sistem donasi
-- database/schema.sql
-- Hapus jika sudah ada dan buat ulang

DROP TABLE IF EXISTS `log_pembayaran`;
DROP TABLE IF EXISTS `feedback`;
DROP TABLE IF EXISTS `pencairan`;
DROP TABLE IF EXISTS `donasi`;
DROP TABLE IF EXISTS `kampanye`;
DROP TABLE IF EXISTS `organisasi`;
DROP TABLE IF EXISTS `users`;

-- Table users
CREATE TABLE `users` (
  `id_pengguna` int(11) NOT NULL AUTO_INCREMENT,
  `nama_pengguna` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` enum('donatur','admin','organisasi') DEFAULT 'donatur',
  `alamat` text DEFAULT NULL,
  `no_hp` varchar(15) DEFAULT NULL,
  `dibuat_pada` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_pengguna`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Table organisasi
CREATE TABLE `organisasi` (
  `id_organisasi` int(11) NOT NULL AUTO_INCREMENT,
  `nama_organisasi` varchar(150) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `email_kontak` varchar(100) DEFAULT NULL,
  `no_kontak` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id_organisasi`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Table kampanye
CREATE TABLE `kampanye` (
  `id_kampanye` int(11) NOT NULL AUTO_INCREMENT,
  `id_organisasi` int(11) DEFAULT NULL,
  `dibuat_oleh` int(11) DEFAULT NULL,
  `judul_kampanye` varchar(150) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `target_dana` decimal(15,2) DEFAULT NULL,
  `dana_terkumpul` decimal(15,2) DEFAULT 0.00,
  `tgl_mulai` date DEFAULT NULL,
  `tgl_selesai` date DEFAULT NULL,
  `status` enum('aktif','selesai','dibatalkan') DEFAULT 'aktif',
  `dibuat_pada` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_kampanye`),
  KEY `id_organisasi` (`id_organisasi`),
  KEY `dibuat_oleh` (`dibuat_oleh`),
  CONSTRAINT `kampanye_ibfk_1` FOREIGN KEY (`id_organisasi`) REFERENCES `organisasi` (`id_organisasi`),
  CONSTRAINT `kampanye_ibfk_2` FOREIGN KEY (`dibuat_oleh`) REFERENCES `users` (`id_pengguna`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Table donasi
CREATE TABLE `donasi` (
  `id_donasi` int(11) NOT NULL AUTO_INCREMENT,
  `id_pengguna` int(11) DEFAULT NULL,
  `id_kampanye` int(11) DEFAULT NULL,
  `jumlah_donasi` decimal(15,2) DEFAULT NULL,
  `metode_pembayaran` varchar(50) DEFAULT NULL,
  `status` enum('pending','berhasil','gagal') DEFAULT 'pending',
  `tgl_donasi` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_donasi`),
  KEY `id_pengguna` (`id_pengguna`),
  KEY `id_kampanye` (`id_kampanye`),
  CONSTRAINT `donasi_ibfk_1` FOREIGN KEY (`id_pengguna`) REFERENCES `users` (`id_pengguna`),
  CONSTRAINT `donasi_ibfk_2` FOREIGN KEY (`id_kampanye`) REFERENCES `kampanye` (`id_kampanye`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Table feedback
CREATE TABLE `feedback` (
  `id_feedback` int(11) NOT NULL AUTO_INCREMENT,
  `id_pengguna` int(11) DEFAULT NULL,
  `id_kampanye` int(11) DEFAULT NULL,
  `isi_feedback` text DEFAULT NULL,
  `rating` int(11) DEFAULT NULL,
  `dibuat_pada` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_feedback`),
  KEY `id_pengguna` (`id_pengguna`),
  KEY `id_kampanye` (`id_kampanye`),
  CONSTRAINT `feedback_ibfk_1` FOREIGN KEY (`id_pengguna`) REFERENCES `users` (`id_pengguna`),
  CONSTRAINT `feedback_ibfk_2` FOREIGN KEY (`id_kampanye`) REFERENCES `kampanye` (`id_kampanye`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Table pencairan
CREATE TABLE `pencairan` (
  `id_cair` int(11) NOT NULL AUTO_INCREMENT,
  `id_kampanye` int(11) DEFAULT NULL,
  `jumlah_cair` decimal(15,2) DEFAULT NULL,
  `tgl_cair` date DEFAULT NULL,
  `status` enum('diproses','berhasil','ditolak') DEFAULT 'diproses',
  PRIMARY KEY (`id_cair`),
  KEY `id_kampanye` (`id_kampanye`),
  CONSTRAINT `pencairan_ibfk_1` FOREIGN KEY (`id_kampanye`) REFERENCES `kampanye` (`id_kampanye`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Table log_pembayaran
CREATE TABLE `log_pembayaran` (
  `id_log` int(11) NOT NULL AUTO_INCREMENT,
  `id_donasi` int(11) DEFAULT NULL,
  `kode_respon` varchar(50) DEFAULT NULL,
  `pesan_respon` text DEFAULT NULL,
  `dibuat_pada` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_log`),
  KEY `id_donasi` (`id_donasi`),
  CONSTRAINT `log_pembayaran_ibfk_1` FOREIGN KEY (`id_donasi`) REFERENCES `donasi` (`id_donasi`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Insert default admin user
INSERT INTO `users` (`nama_pengguna`, `email`, `password`, `role`, `alamat`, `no_hp`) VALUES
('Admin Sistem', 'admin@donasi.com', 'admin123', 'admin', 'Jakarta', '081234567890');

-- Insert sample organizations
INSERT INTO `organisasi` (`nama_organisasi`, `alamat`, `email_kontak`, `no_kontak`) VALUES
('Yayasan Peduli Bangsa', 'Jakarta', 'kontak@ypb.org', '021111111'),
('Relawan Sehat Indonesia', 'Bandung', 'info@rsi.org', '022222222'),
('Dompet Kemanusiaan', 'Surabaya', 'donasi@dk.org', '031333333'),
('Aksi Cepat Tanggap Lokal', 'Yogyakarta', 'aksi@actl.org', '027444444'),
('Pecinta Lingkungan Hidup', 'Bali', 'green@plh.org', '036155555');

-- Insert sample campaigns
INSERT INTO `kampanye` (`id_organisasi`, `dibuat_oleh`, `judul_kampanye`, `deskripsi`, `target_dana`, `dana_terkumpul`, `tgl_mulai`, `tgl_selesai`, `status`) VALUES
(1, 1, 'Bantu Anak Sekolah', 'Donasi perlengkapan sekolah', 5000000.00, 1500000.00, '2025-01-01', '2025-03-01', 'aktif'),
(2, 1, 'Donasi Pengobatan', 'Bantuan biaya rumah sakit', 10000000.00, 2500000.00, '2025-01-05', '2025-04-05', 'aktif'),
(3, 1, 'Pangan untuk Lansia', 'Sembako gratis bagi lansia', 7500000.00, 0.00, '2025-02-01', '2025-05-01', 'aktif'),
(4, 1, 'Renovasi Mushola', 'Perbaikan atap mushola desa', 20000000.00, 5000000.00, '2025-01-10', '2025-06-10', 'aktif'),
(5, 1, 'Tanam 1000 Pohon', 'Penghijauan pesisir pantai', 3000000.00, 1000000.00, '2025-03-01', '2025-04-01', 'aktif');