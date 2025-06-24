<p align="center">
  <img src="https://i.imgur.com/Np2FqhJ.png" width="300" alt="Web Inventaris Logo">
</p>

<p align="center">
  <a href="#"><img src="https://img.shields.io/badge/status-in%20progress-yellow" alt="Project Status"></a>
  <a href="https://github.com/JustThiriq/web-inventaris/issues"><img src="https://img.shields.io/github/issues/JustThiriq/web-inventaris" alt="Open Issues"></a>
  <a href="https://github.com/JustThiriq/web-inventaris/blob/main/LICENSE"><img src="https://img.shields.io/github/license/JustThiriq/web-inventaris" alt="License"></a>
</p>

---

# Web Inventaris 🧾

Aplikasi web berbasis Laravel 11 untuk manajemen inventaris barang, pendataan stok masuk/keluar, pelaporan, dan pengingat restock. Proyek ini saat ini sedang dalam tahap pengembangan dan menggunakan template AdminLTE untuk halaman dashboard.

## 📖 Daftar Isi

* [Tentang](#tentang)
* [Fitur](#fitur)
* [Demo](#demo)
* [Instalasi](#instalasi)
* [Penggunaan](#penggunaan)
* [Arsitektur & Teknologi](#arsitektur--teknologi)
* [Kontribusi](#kontribusi)
* [Lisensi](#lisensi)

## Tentang

Web Inventaris adalah aplikasi manajemen stok barang berbasis Laravel 11. Fokus utama aplikasi ini adalah membantu pengguna mencatat barang masuk dan keluar, mengelola stok, serta menghasilkan laporan yang relevan. Saat ini, pengembangan masih berada pada tahap integrasi template AdminLTE pada halaman dashboard.

## Fitur

* CRUD data barang
* Pencatatan stok masuk dan keluar
* Laporan stok harian, mingguan, bulanan
* Peringatan saat stok minimum tercapai
* Autentikasi pengguna dan peran admin/user (dalam pengembangan)

## Demo

Belum tersedia. Fitur demo akan ditambahkan setelah pengembangan UI utama selesai.

## Instalasi

Langkah-langkah untuk menjalankan project secara lokal:

```bash
git clone https://github.com/JustThiriq/web-inventaris.git
cd web-inventaris

composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve
```

> Pastikan Anda sudah memiliki database MySQL dan Laravel 11 terinstal dengan PHP >= 8.2.

## Penggunaan

1. Akses aplikasi melalui `http://localhost:8000`
2. Login (jika fitur autentikasi sudah tersedia)
3. Navigasi ke dashboard berbasis AdminLTE
4. Tambahkan data barang, stok masuk/keluar, dll

## Arsitektur & Teknologi

* **Framework**: Laravel 11
* **Template**: AdminLTE (versi terakhir)
* **Frontend**: Blade, HTML, CSS, JavaScript
* **Backend**: PHP 8.2+
* **Database**: MySQL
* **Tools**: Laravel Artisan, Composer

## Kontribusi

Kontributor sangat diterima. Cara berkontribusi:

1. Fork repository ini
2. Buat branch baru: `feat/nama-fitur`
3. Commit perubahan
4. Push ke repo Anda
5. Buat Pull Request

## Lisensi

MIT License © 2025 JustThiriq dan iminervaa69

---

Dibuat oleh [JustThiriq](https://github.com/JustThiriq) dan [iminervaa69](https://github.com/iminervaa69) – feel free to connect!
