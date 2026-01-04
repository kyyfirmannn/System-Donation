# Sistem Donasi Berbasis Web

Proyek ini merupakan **Tugas Besar Praktikum Basis Data** berupa **website sistem donasi** yang dibangun menggunakan **PHP (native)** dan **MySQL** dengan **XAMPP** sebagai web server.  
Perancangan sistem dan implementasi database **menyesuaikan dengan ERD yang telah dibuat**.

---

## 📌 Deskripsi Proyek
Sistem Donasi ini digunakan untuk mengelola proses donasi secara terstruktur, mulai dari:
- Manajemen pengguna (admin & user)
- Pengelolaan kampanye donasi oleh organisasi
- Pencatatan transaksi donasi
- Proses pencairan dana
- Feedback dan pelaporan donasi

Sistem ini menitikberatkan pada **implementasi basis data**, relasi antar tabel, serta penggunaan query SQL sesuai dengan konsep yang dipelajari pada mata kuliah Praktikum Basis Data.

---

## 🧩 Fitur Utama
### User
- Melihat daftar kampanye donasi
- Melakukan donasi
- Melihat riwayat donasi
- Memberikan feedback

### Admin
- Mengelola data pengguna
- Mengelola kampanye donasi
- Melihat data donasi
- Mengelola pencairan dana
- Melihat laporan donasi

---

## 🗃️ Entitas Database
- Users
- Organisasi
- Kampanye
- Donasi
- Log_Pembayaran
- Pencairan
- Feedback

Semua entitas dirancang berdasarkan **ERD sistem donasi**.

---

## 📂 Struktur Folder
```
sistem-donasi/
├── backend/
│ ├── config/ # Koneksi database
│ ├── core/ # Auth & middleware
│ ├── models/ # Representasi tabel database
│ ├── controllers/ # Logic aplikasi
│ └── routes/
│
├── frontend/
│ ├── user/ # Halaman user
│ ├── admin/ # Halaman admin
│ └── assets/ # CSS, JS, Images
│
├── database/
│ ├── schema.sql # Struktur tabel
│ ├── seed.sql # Data dummy
│ └── query/ # Query JOIN & laporan
│
├── docs/
│ └── ERD.png
│
├── README.md
└── .gitignore
```
---

## 🛠 Teknologi yang Digunakan
- Bahasa Pemrograman : PHP (Native)
- Database : MySQL
- Web Server : XAMPP (Apache & MySQL)
- Tools : VS Code, phpMyAdmin
- Version Control : Git & GitHub

---

## ⚙️ Cara Menjalankan Proyek
1. Install **XAMPP**
2. Pindahkan folder project ke: C:\xampp\htdocs\
3. Jalankan **Apache** dan **MySQL**
4. Buat database `sistem_donasi` melalui phpMyAdmin
5. Import file `database/schema.sql`
6. Akses melalui browser:
- User:
  ```
  http://localhost/sistem-donasi/frontend/user
  ```
- Admin:
  ```
  http://localhost/sistem-donasi/frontend/admin
  ```

---

## 👥 Anggota Kelompok
- RYESISCA TAJWITTA MULYADI-2450081100
- RIZKY FIRMANSYAH-2450081107
- SOFYAN HADI SUMARNO-2450081111

---

## 📄 Catatan
Proyek ini dibuat untuk keperluan akademik dan difokuskan pada penerapan konsep **perancangan dan implementasi basis data**.