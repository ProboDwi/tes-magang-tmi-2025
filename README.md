# 📦 Sistem Transaksi Produk

Sistem manajemen transaksi produk berbasis web menggunakan **Laravel** dan **AdminLTE**. Aplikasi ini memungkinkan pengguna untuk mengelola data produk, kategori, dan transaksi. Tersedia fitur **import/export Excel**, **export PDF**, dan **notifikasi email otomatis via SMTP** saat transaksi terjadi.

---

## 🙋‍♂️ Identitas Pengembang

- **Nama**: Probo Dwi Wahyudi  
- **Kampus**: Politeknik Negeri Cilacap  
- **Program**: Tes Magang di TMI (Tanjung Mulia Informatika)

---

## 🚀 Fitur Utama

- ✅ CRUD Produk
- ✅ CRUD Kategori
- ✅ CRUD Transaksi
- ✅ Export data ke **PDF**
- ✅ Export data ke **Excel**
- ✅ Import data dari **Excel**
- ✅ **Notifikasi Email Otomatis (SMTP)** saat transaksi
- ✅ Tampilan dashboard menggunakan **AdminLTE**

---

## 🗃️ Struktur Tabel

### 1. `kategoris`
- `id` (PK)
- `nama` (string)

### 2. `produks`
- `id` (PK)
- `nama` (string)
- `harga` (integer)
- `stok` (integer)
- `kategori_id` (FK ke `kategoris`)

### 3. `transaksis`
- `id` (PK)
- `produk_id` (FK ke `produks`)
- `jumlah` (integer)
- `total_harga` (integer)
- `tanggal_transaksi` (date)

---

## ⚙️ Teknologi

- [Laravel 10+](https://laravel.com/)
- [AdminLTE 3+](https://adminlte.io/)
- [Maatwebsite Laravel Excel](https://laravel-excel.com/) – Import/Export Excel
- [DomPDF](https://github.com/barryvdh/laravel-dompdf) – Export PDF
- [SMTP Email (Laravel Mail)](https://laravel.com/docs/mail) – Notifikasi transaksi

---

## 🧑‍💻 Instalasi

```bash
# 1. Clone repositori ini
git clone https://github.com/username/nama-proyek.git
cd nama-proyek

# 2. Install dependency
composer install
npm install && npm run dev

# 3. Buat file .env dan generate key
cp .env.example .env
php artisan key:generate

# 4. Setup database
# Edit .env sesuai koneksi database Anda, lalu jalankan:
php artisan migrate --seed

# 5. Setup konfigurasi email (SMTP)
# Isi konfigurasi berikut di file .env:
MAIL_MAILER=smtp
MAIL_HOST=smtp.example.com
MAIL_PORT=587
MAIL_USERNAME=your@email.com
MAIL_PASSWORD=yourpassword
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your@email.com
MAIL_FROM_NAME="Sistem Transaksi"

# 6. Jalankan server lokal
php artisan serve
