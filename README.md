# Asset Management System

![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![AdminLTE](https://img.shields.io/badge/AdminLTE-3C8DBC?style=for-the-badge&logo=adminlte&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white)

## 📖 Deskripsi

**Asset Management System** adalah aplikasi web yang dikembangkan untuk mengelola dan melacak aset-aset perusahaan secara efisien. Aplikasi ini memungkinkan pencatatan yang terstruktur terhadap seluruh aset yang dimiliki, dilengkapi dengan sistem peminjaman yang terintegrasi. Dengan antarmuka yang user-friendly berbasis AdminLTE, pengguna dapat dengan mudah melakukan tracking status aset, mengajukan peminjaman, dan mengelola data aset secara real-time.

Aplikasi ini dikembangkan sebagai proyek Praktek Kerja Lapangan (PKL) dan akan digunakan untuk keperluan sidang PKL.

## ✨ Fitur Utama

### 🏷️ Manajemen Aset
- 📋 Pendaftaran dan katalog aset terpusat
- 🏷️ Kategori aset yang terorganisir
- 🔢 Kode aset unik untuk identifikasi
- 📊 Status tracking aset (tersedia, dipinjam, maintenance)

### 🔄 Sistem Peminjaman
- 📝 Formulir peminjaman aset yang terstruktur
- ⏳ Status peminjaman (menunggu, disetujui, ditolak, dikembalikan)
- 📅 Tracking tanggal pinjam dan kembali
- 👥 History peminjaman per user

### 👥 Multi-User Role System
- **Administrator**
  - Mengelola data aset dan kategori
  - Management user accounts
  - Approval/rejection peminjaman
  - Monitoring seluruh aktivitas sistem

- **User**
  - Melihat katalog aset tersedia
  - Mengajukan peminjaman aset
  - Melihat status peminjaman
  - History peminjaman pribadi

## 🛠 Teknologi yang Digunakan

### Backend
- **Laravel 10** - PHP Framework
- **Eloquent ORM** - Database Management
- **Laravel Authentication** - Security System

### Frontend
- **AdminLTE 3** - Admin Dashboard Template
- **Bootstrap 5** - CSS Framework
- **jQuery** - JavaScript Library
- **Font Awesome** - Icons
- **DataTables** - Table Enhancement

### Database
- **MySQL** - Database Management System

### Development Tools
- **Composer** - PHP Dependency Manager
- **npm** - Node Package Manager

## 🚀 Instalasi dan Konfigurasi

### Prerequisites
Pastikan sistem Anda memenuhi requirements berikut:
- PHP 8.1 atau lebih tinggi
- Composer
- MySQL 5.7+ atau MariaDB 10.3+
- Web Server (Apache/Nginx)
- Node.js & npm (untuk assets)

### Langkah-langkah Instalasi

1. **Clone Repository**
   ```bash
   git clone https://github.com/username/asset-management.git
   cd asset-management

2. **Install PHP Dependencies**
   ```bash
   composer install

3. **Install JavaScript Dependencies**
   ```bash
   npm install

4. **Setup Environment File**
   ```bash
   cp .env.example .env
   php artisan key:generate

5. **Setup Environment File**
   ```bash
   a. Buat database MySQL baru dengan nama: asset_management
   b. Edit file .env dan sesuaikan konfigurasi database:
     DB_CONNECTION=mysql
     DB_HOST=127.0.0.1
     DB_PORT=3306
     DB_DATABASE=asset_management
     DB_USERNAME=root
     DB_PASSWORD=password_anda

6. **Jalankan Database Migration & Seeder**
   ```bash
   php artisan migrate --seed

7. **Build Frontend Assets**
   ```bash
   npm run build

8. **Buat Storage Link**
   ```bash
   php artisan storage:link

9. **Jalankan Development Server**
   ```bash
   php artisan serve

10. **Akses Aplikasi**
   ```bash
   a. Buka browser dan kunjungi: http://localhost:8000
   b. Login Default:
      Admin: admin@example.com / password
      User: user@example.com / password
