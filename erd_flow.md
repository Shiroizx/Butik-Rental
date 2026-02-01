# Dokumentasi Struktur Database & ERD

Dokumen ini berisi detail teknis struktur database (schema) dan hubungan antar entitas (relationships) untuk **LuxeBoutique**. Gunakan informasi ini untuk membuat diagram ERD yang akurat.

## 1. Kamus Data (Data Dictionary)

Detail kolom dan tipe data untuk setiap tabel.

### A. Master Data

| Tabel | Kolom | Tipe Data | Keterangan |
| :--- | :--- | :--- | :--- |
| **customers** | `id` | PK (BigInt) | Primary Key |
| | `nama` | String(100) | Nama lengkap pelanggan |
| | `alamat` | Text | Alamat domisili |
| | `no_telepon` | String(20) | Nomor HP/WA |
| | `email` | String(100) | Email (Opsional) |
| | | | |
| **employees** | `id` | PK (BigInt) | Primary Key |
| | `nama` | String(100) | Nama karyawan |
| | `role` | Enum | 'admin' atau 'staff' |
| | `email` | String(100) | Login email (Unique) |
| | `password` | String | Hashed password |
| | | | |
| **categories** | `id` | PK (BigInt) | Primary Key |
| | `nama_kategori`| String(100) | Contoh: Kebaya, Jas, Gaun |
| | | | |
| **clothes** | `id` | PK (BigInt) | Primary Key |
| | `category_id` | FK (BigInt) | Relasi ke `categories` |
| | `nama_baju` | String(150) | Nama produk |
| | `harga_sewa` | Decimal | Biaya sewa per hari |
| | `warna` | String(50) | Warna dominan |
| | `gambar` | String | Path file gambar |
| | `is_available` | Boolean | Status ketersediaan umum |
| | | | |
| **clothes_sizes** | `id` | PK (BigInt) | Primary Key |
| | `clothes_id` | FK (BigInt) | Relasi ke `clothes` |
| | `ukuran` | String | Ukuran (S, M, L, XL, All Size) |
| | `stok` | Integer | Jumlah fisik barang per ukuran |

### B. Transaksi (Transaction)

| Tabel | Kolom | Tipe Data | Keterangan |
| :--- | :--- | :--- | :--- |
| **rentals** | `id` | PK (BigInt) | Primary Key |
| | `kode_transaksi`| String(50) | Kode unik (TRX-...) |
| | `customer_id` | FK (BigInt) | Peminjam |
| | `employee_id` | FK (BigInt) | Petugas yang melayani |
| | `tanggal_pinjam`| Date | Tanggal mulai sewa |
| | `tanggal_kembali`| Date | Rencana tanggal kembali |
| | `total_biaya` | Decimal | Total tagihan sewa |
| | `deposit` | Decimal | Uang jaminan |
| | `status` | Enum | pending, aktif, selesai, batal |
| | | | |
| **rental_details** | `id` | PK (BigInt) | Primary Key |
| | `rental_id` | FK (BigInt) | Relasi ke Header `rentals` |
| | `clothes_id` | FK (BigInt) | Relasi ke Barang |
| | `clothes_size_id`| FK (BigInt) | Relasi ke Ukuran Spesifik |
| | `jumlah` | Integer | Jumlah item disewa |
| | `harga_satuan` | Decimal | Harga saat transaksi (snapshot) |
| | `subtotal` | Decimal | Jumlah * Harga Satuan |
| | | | |
| **payments** | `id` | PK (BigInt) | Primary Key |
| | `rental_id` | FK (BigInt) | Relasi ke `rentals` |
| | `nominal` | Decimal | Jumlah yang dibayarkan |
| | `metode_bayar` | Enum | cash, transfer, dll |
| | `status_bayar` | Enum | pending, lunas |
| | | | |
| **returns** | `id` | PK (BigInt) | Primary Key |
| | `rental_id` | FK (BigInt) | (Opsional) Header Peminjaman |
| | `rental_detail_id`| FK (BigInt) | Relasi ke Item Spesifik yg kembali |
| | `diterima_oleh` | FK (BigInt) | Karyawan penerima (`employees`) |
| | `tanggal_kembali_aktual`| Date | Tanggal realisasi kembali |
| | `kondisi` | Enum | baik, rusak, hilang |
| | | | |
| **fines** | `id` | PK (BigInt) | Primary Key |
| | `rental_id` | FK (BigInt) | Relasi ke `rentals` |
| | `return_id` | FK (BigInt) | Relasi ke `returns` (jika denda item) |
| | `jumlah_denda` | Decimal | Nominal denda |
| | `jenis_denda` | Enum | telat, rusak, hilang |

---

## 2. Hubungan Antar Tabel (Entity Relationships)

Berikut adalah detail kardinalitas untuk digambarkan garisnya di ERD.

### One-to-Many (1:N)
Satu baris di Tabel A terhubung ke BANYAK baris di Tabel B.

1.  **Category -> Clothes**
    *   *Penjelasan*: Satu Kategori (misal: "Jas") memiliki banyak Data Baju.
2.  **Cloth -> ClothesSizes**
    *   *Penjelasan*: Satu Model Baju (misal: "Tuxedo Hitam") memiliki banyak varian ukuran (S, M, L).
3.  **Customer -> Rentals**
    *   *Penjelasan*: Satu Pelanggan bisa melakukan booking/sewa berkali-kali (History Transaksi).
4.  **Employee -> Rentals**
    *   *Penjelasan*: Satu Karyawan bisa melayani/mencatat banyak transaksi sewa.
5.  **Rental -> RentalDetails**
    *   *Penjelasan*: Satu Nomor Transaksi (Invoice) berisi banyak item barang yang disewa.
6.  **Rental -> Payments**
    *   *Penjelasan*: Satu Transaksi bisa dibayar nyicil (DP dulu, lalu Pelunasan), jadi bisa ada banyak record pembayaran.
7.  **Rental -> Returns**
    *   *Penjelasan*: Satu Transaksi sewa akan memiliki catatan pengembalian (bisa per item).

### Many-to-Many (N:M)
Hubungan Banyak-ke-Banyak yang dijembatani oleh tabel perantara (Pivot Table).

1.  **Rental <-> Clothes** (via `rental_details`)
    *   *Konsep*: Satu transaksi sewa bisa meminjam banyak baju. Satu baju bisa disewa dalam banyak transaksi berbeda (beda waktu).
    *   *Implementasi*: Tabel **rental_details** menghubungkan `rental_id` dan `clothes_id`.

---

## 3. Catatan Penting untuk Design ERD

*   **Pivot Table**: `rental_details` adalah tabel paling krusial karena menghubungkan transaksi dengan inventory.
*   **Stok Real**: Stok fisik disimpan di `clothes_sizes`, bukan di `clothes`. Saat transaksi terjadi, stok di `clothes_sizes` berkurang.
*   **Snapshot Harga**: `rental_details` menyimpan `harga_satuan`. Ini penting agar jika harga sewa baju naik di masa depan, data history transaksi lama harganya tidak ikut berubah.
