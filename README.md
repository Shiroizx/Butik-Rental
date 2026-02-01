# LuxeBoutique - Sistem Rental Baju 👑

Sistem manajemen penyewaan baju butik berbasis web yang modern dan responsif. Aplikasi ini membantu mengelola inventaris baju, transaksi penyewaan, pengembalian, denda, dan pelaporan dengan antarmuka yang elegan (Premium Dark/Gold Theme).

## 🚀 Fitur Utama

*   **Dashboard Interaktif**: Ringkasan data penyewaan, pelanggan, dan inventaris.
*   **Manajemen Inventaris**: CRUD Baju, Kategori, dan Stok Ukuran (S, M, L, XL).
*   **Transaksi Sewa**: Pencatatan sewa, deposit, dan hitung total otomatis.
*   **Pengembalian & Denda**: Fitur pengembalian dengan perhitungan denda otomatis (terlambat/rusak).
*   **Laporan**: Riwayat transaksi dan status pembayaran.
*   **Dark Mode**: Mendukung mode gelap/terang dengan toggle yang persisten.

## 🛠️ Teknologi yang Digunakan

*   **Framework**: Laravel 10+
*   **Database**: MySQL
*   **Frontend**: Blade Templates, Tailwind CSS
*   **Icons**: Lucide Icons

## ⚙️ Cara Instalasi (Installation Guide)

Ikuti langkah-langkah berikut untuk menjalankan project ini di komputer lokal Anda.

### Prasyarat
Pastikan Anda sudah menginstall:
*   PHP >= 8.1
*   Composer
*   Node.js & NPM
*   MySQL

### Langkah Instalasi

1.  **Clone Repository**
    ```bash
    git clone https://github.com/Shiroizx/Butik-Rental.git
    cd Butik-Rental
    ```

2.  **Install Dependencies**
    Install dependency PHP dan JavaScript:
    ```bash
    composer install
    npm install
    ```

3.  **Setup Environment**
    Salin file `.env.example` menjadi `.env`:
    ```bash
    cp .env.example .env
    ```
    Buka file `.env` dan sesuaikan konfigurasi database Anda:
    ```env
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=butik_rental_system
    DB_USERNAME=root
    DB_PASSWORD=
    ```

4.  **Generate App Key**
    ```bash
    php artisan key:generate
    ```

5.  **Migrasi Database & Seeder**
    Jalankan migrasi untuk membuat tabel dan mengisi data dummy awal:
    ```bash
    php artisan migrate --seed
    ```
    *Catatan: Seeder akan membuat akun admin default dan data inventaris dummy.*

6.  **Jalankan Aplikasi**
    Buka dua terminal terpisah untuk menjalankan server backend dan frontend:

    *Terminal 1 (Laravel Server):*
    ```bash
    php artisan serve
    ```

    *Terminal 2 (Vite Development Server):*
    ```bash
    npm run dev
    ```

7.  **Akses Aplikasi**
    Buka browser dan kunjungi: `http://127.0.0.1:8000`

## 👤 Akun Default (Seeder)

*   **Email**: `admin@butik.com`
*   **Password**: `password`

---
Dibuat dengan ❤️ untuk kemudahan manajemen butik Anda.
