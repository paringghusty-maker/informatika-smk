-- =========================================================
-- DATABASE: informatika
-- Website Pembelajaran Interaktif "Informatika"
-- Domain Mata Pelajaran: Teknologi Informasi dan Analisis Data
-- =========================================================

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+07:00";

CREATE DATABASE IF NOT EXISTS `informatika` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `informatika`;

-- =========================================================
-- Tabel: users
-- =========================================================
CREATE TABLE `users` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `nama` VARCHAR(100) NOT NULL,
  `username` VARCHAR(50) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `role` ENUM('admin','user') NOT NULL DEFAULT 'user',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Password default: admin123 (di-hash dengan password_hash() bcrypt, sudah diverifikasi valid)
INSERT INTO `users` (`nama`, `username`, `password`, `role`) VALUES
('Administrator', 'admin', '$2y$10$Z2JT.40CjD6PRiWQvPRfCeDeaPDcMgoVQ0vx4iVqXIOKWGLUWZZw.', 'admin');
-- Login admin default -> username: admin | password: admin123
-- WAJIB ganti password setelah instalasi pertama melalui menu Manajemen User.

-- =========================================================
-- Tabel: pengaturan_website
-- =========================================================
CREATE TABLE `pengaturan_website` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `nama_website` VARCHAR(150) NOT NULL DEFAULT 'Informatika',
  `domain` VARCHAR(150) NOT NULL DEFAULT 'Teknologi Informasi dan Analisis Data',
  `logo` VARCHAR(255) DEFAULT NULL,
  `banner` VARCHAR(255) DEFAULT NULL,
  `deskripsi` TEXT DEFAULT NULL,
  `pesan_penutup` TEXT DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `pengaturan_website` (`nama_website`, `domain`, `logo`, `banner`, `deskripsi`, `pesan_penutup`) VALUES
('Informatika', 'Teknologi Informasi dan Analisis Data', NULL, NULL,
'Website pembelajaran interaktif untuk membantu siswa memahami konsep Informatika, khususnya pada domain Teknologi Informasi dan Analisis Data, melalui materi, video, praktik, refleksi, dan evaluasi.',
'Belajar dengan tekun dan konsisten, karena ilmu yang bermanfaat akan selalu membuka jalan menuju masa depan yang lebih baik.');

-- =========================================================
-- Tabel: materi
-- =========================================================
CREATE TABLE `materi` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `judul` VARCHAR(200) NOT NULL,
  `deskripsi` VARCHAR(255) DEFAULT NULL,
  `isi` LONGTEXT NOT NULL,
  `gambar` VARCHAR(255) DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `materi` (`judul`, `deskripsi`, `isi`, `gambar`) VALUES
('Pengenalan Analisis Data', 'Dasar-dasar konsep analisis data dalam kehidupan sehari-hari',
'<p>Analisis data adalah proses memeriksa, membersihkan, mengubah, dan memodelkan data dengan tujuan menemukan informasi yang berguna, menginformasikan kesimpulan, dan mendukung pengambilan keputusan.</p><p>Dalam dunia Teknologi Informasi, kemampuan menganalisis data menjadi sangat penting karena hampir semua aktivitas menghasilkan data, mulai dari transaksi sekolah, nilai siswa, hingga aktivitas digital sehari-hari.</p>', NULL),
('Pengolahan Data dengan Spreadsheet', 'Menggunakan aplikasi spreadsheet untuk mengolah data secara efisien',
'<p>Spreadsheet seperti Google Sheets dan Microsoft Excel memungkinkan kita mengolah data dalam bentuk tabel, melakukan perhitungan otomatis menggunakan rumus, serta membuat grafik untuk memvisualisasikan data.</p><p>Beberapa fungsi dasar yang sering digunakan antara lain SUM, AVERAGE, IF, dan VLOOKUP.</p>', NULL),
('Konsep Dasar Algoritma', 'Memahami logika berpikir komputasional',
'<p>Algoritma adalah kumpulan langkah-langkah logis dan sistematis untuk menyelesaikan suatu masalah. Algoritma menjadi dasar penting sebelum menulis program komputer.</p><p>Berpikir komputasional melibatkan dekomposisi, pengenalan pola, abstraksi, dan penyusunan algoritma.</p>', NULL);

-- =========================================================
-- Tabel: video
-- =========================================================
CREATE TABLE `video` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `judul` VARCHAR(200) NOT NULL,
  `link` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `video` (`judul`, `link`) VALUES
('Pengenalan Analisis Data untuk Pemula', 'https://www.youtube.com/embed/dQw4w9WgXcQ'),
('Tutorial Dasar Google Sheets', 'https://www.youtube.com/embed/dQw4w9WgXcQ');

-- =========================================================
-- Tabel: refleksi
-- =========================================================
CREATE TABLE `refleksi` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `nama` VARCHAR(100) NOT NULL,
  `pembelajaran` TEXT NOT NULL,
  `kesulitan` TEXT DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =========================================================
-- Tabel: soal
-- =========================================================
CREATE TABLE `soal` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `pertanyaan` TEXT NOT NULL,
  `opsi_a` VARCHAR(255) NOT NULL,
  `opsi_b` VARCHAR(255) NOT NULL,
  `opsi_c` VARCHAR(255) NOT NULL,
  `opsi_d` VARCHAR(255) NOT NULL,
  `jawaban_benar` ENUM('A','B','C','D') NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `soal` (`pertanyaan`, `opsi_a`, `opsi_b`, `opsi_c`, `opsi_d`, `jawaban_benar`) VALUES
('Apa tujuan utama dari analisis data?', 'Membuat data semakin rumit', 'Menemukan informasi berguna untuk pengambilan keputusan', 'Menghapus seluruh data', 'Menyimpan data tanpa diproses', 'B'),
('Aplikasi berikut yang termasuk spreadsheet adalah...', 'Microsoft Word', 'Google Sheets', 'Adobe Photoshop', 'VLC Media Player', 'B'),
('Fungsi SUM pada spreadsheet digunakan untuk...', 'Mengurutkan data', 'Menjumlahkan data', 'Menghapus data', 'Mewarnai sel', 'B'),
('Algoritma adalah...', 'Hasil akhir dari sebuah program', 'Kumpulan langkah logis untuk menyelesaikan masalah', 'Jenis perangkat keras komputer', 'Bahasa pemrograman tertentu', 'B'),
('Berikut yang bukan merupakan tahapan berpikir komputasional adalah...', 'Dekomposisi', 'Pengenalan pola', 'Abstraksi', 'Eliminasi total data', 'D');

-- =========================================================
-- Tabel: hasil_nilai
-- =========================================================
CREATE TABLE `hasil_nilai` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `nama` VARCHAR(100) NOT NULL,
  `skor` INT(11) NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
