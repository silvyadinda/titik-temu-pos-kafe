# Sistem Kasir & Pemesanan Kafe Modern

Tech stack: PHP Native (kompatibel PHP 5.6.40), MySQL/MariaDB, Bootstrap 5.

## Instalasi XAMPP
1. Salin folder `kafe_pos` ke `C:\xampp\htdocs\`.
2. Jalankan Apache dan MySQL.
3. Buka phpMyAdmin lalu import `database/kafe_pos.sql`.
4. Buka `http://localhost/kafe_pos/`.

## Akun Demo
- Admin: `admin` / `admin123`
- Kasir: `kasir` / `kasir123`

## Fitur
- Login, session, role Admin/Kasir, password Bcrypt.
- Dashboard: omzet hari ini, jumlah pesanan, menu terlaris, stok rendah, transaksi terbaru.
- Master kategori, menu, harga, dan stok.
- Dine-In/Takeaway, multi-item, catatan, pajak 10%, diskon, Cash/QRIS/Debit, kembalian.
- Database transaction (commit/rollback) dan pengurangan stok otomatis.
- Invoice otomatis `INV-POS-YYYYMMDD-XXXX` dan cetak struk.
- Laporan periode/metode pembayaran dan ekspor CSV.

Catatan: Bootstrap dimuat melalui CDN, sehingga tampilan optimal membutuhkan internet. Untuk produksi, tambahkan CSRF token, validasi lebih ketat, dan dependency PDF bila ekspor PDF harus berupa file PDF langsung.


## Kompatibilitas
Versi ini telah disesuaikan untuk PHP 5.6.40 (XAMPP lama): tidak menggunakan null coalescing operator, random_int, Throwable, atau array destructuring PHP 7.

## Revisi tampilan
- Tema coklat muda / cream tanpa gambar.
- Halaman kasir menghitung subtotal, pajak 10%, grand total, dan kembalian secara otomatis.
- QRIS/Debit otomatis mengisi nominal sesuai grand total.
- Kompatibel dengan PHP 5.6.40.


## Fitur stok (pembaruan)
- Peringatan visual untuk stok habis (stok 0) dan stok menipis (stok sama dengan atau di bawah batas minimum).
- Dashboard menampilkan daftar menu yang perlu diisi ulang.
- Admin dapat menambah stok langsung dari tabel Menu & Stok; jumlah ditambahkan ke stok yang sudah ada.
- Tidak memerlukan perubahan struktur database karena menggunakan kolom `stok` dan `stok_minimum` yang sudah tersedia.
