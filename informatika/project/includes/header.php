<?php
/**
 * File: includes/header.php
 * Header HTML untuk halaman publik (user).
 * Memuat <head>, pembukaan <body>, dan mengambil pengaturan_website dari database.
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($pdo)) {
    require_once __DIR__ . '/koneksi.php';
}

// Ambil pengaturan website (dinamis dari database)
$stmt = $pdo->query("SELECT * FROM pengaturan_website ORDER BY id ASC LIMIT 1");
$pengaturan = $stmt->fetch();

$nama_website   = $pengaturan['nama_website']   ?? 'Informatika';
$domain_pelajaran = $pengaturan['domain']       ?? 'Teknologi Informasi dan Analisis Data';
$logo_website   = $pengaturan['logo']           ?? null;
$deskripsi_web  = $pengaturan['deskripsi']      ?? '';

$logo_path = $logo_website ? 'assets/uploads/' . $logo_website : null;
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars($nama_website) ?><?= isset($page_title) ? ' - ' . htmlspecialchars($page_title) : '' ?></title>
<meta name="description" content="<?= htmlspecialchars($deskripsi_web) ?>">

<!-- Bootstrap 5 -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
<!-- Google Font -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<!-- Custom CSS -->
<link rel="stylesheet" href="assets/css/style.css">
<?php if ($logo_path): ?>
<link rel="icon" href="<?= htmlspecialchars($logo_path) ?>">
<?php endif; ?>
</head>
<body>
