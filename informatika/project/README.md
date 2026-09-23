# Informatika — Website Pembelajaran Interaktif

Website pembelajaran interaktif untuk mata pelajaran **Informatika**, domain **Teknologi Informasi dan Analisis Data**. Dibangun dengan PHP Native (tanpa framework), MySQL/MariaDB, dan Bootstrap 5.

## Cara Instalasi (XAMPP)

1. **Salin folder project**
   Salin seluruh folder `project` ke dalam direktori `htdocs` XAMPP, misalnya:
   `C:\xampp\htdocs\informatika\`

2. **Jalankan Apache & MySQL**
   Buka XAMPP Control Panel, lalu **Start** pada Apache dan MySQL.

3. **Import database**
   - Buka `http://localhost/phpmyadmin`
   - Klik **New**, buat database baru (atau biarkan, karena file SQL sudah membuat sendiri database `informatika`)
   - Pilih tab **Import**
   - Pilih file `database/informatika.sql`
   - Klik **Go** / **Kirim**

   Atau melalui command line:
   ```
   mysql -u root < database/informatika.sql
   ```

4. **Akses website**
   - Halaman siswa: `http://localhost/informatika/index.php`
   - Halaman admin: `http://localhost/informatika/admin/login.php`

## Login Admin Default

| Username | Password   |
|----------|------------|
| admin    | admin123   |

> ⚠️ **Penting:** Segera ganti password default setelah instalasi pertama melalui menu **Manajemen User** di panel admin.

## Struktur Folder

```
project/
├── index.php          Beranda
├── materi.php          Materi & Video pembelajaran
├── praktik.php         Halaman praktik (Google Sheets)
├── refleksi.php        Form refleksi siswa
├── evaluasi.php        Soal evaluasi & penilaian otomatis
├── assets/             CSS, JS, gambar, file upload
├── includes/           File bersama (koneksi, header, navbar, footer, auth)
├── admin/              Panel admin (CRUD materi, video, soal, dll)
└── database/
    └── informatika.sql Skema & data awal database
```

## Fitur Utama

**Siswa (tanpa login):**
- Membaca materi & menonton video pembelajaran
- Mengakses halaman praktik dan file latihan
- Mengisi refleksi pembelajaran
- Mengerjakan evaluasi pilihan ganda dengan penilaian otomatis

**Admin (login diperlukan):**
- Mengelola pengaturan website (nama, logo, banner, deskripsi, pesan penutup)
- CRUD materi (dengan upload gambar)
- CRUD video pembelajaran (otomatis konversi link YouTube ke embed)
- CRUD soal evaluasi
- Melihat hasil refleksi siswa
- Melihat & mengelola nilai siswa
- Manajemen akun pengguna (admin/user)

## Catatan Teknis

- Password disimpan menggunakan `password_hash()` (bcrypt) — aman dan tidak plain text.
- Koneksi database menggunakan PDO dengan prepared statements (aman dari SQL Injection).
- Validasi form di sisi client (Bootstrap validation) dan server (PHP).
- Notifikasi menggunakan SweetAlert2, tabel admin menggunakan DataTables.
- Folder `assets/uploads/` harus memiliki izin tulis (writable) oleh web server.

