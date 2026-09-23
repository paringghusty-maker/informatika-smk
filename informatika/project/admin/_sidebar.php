<?php
/**
 * File: admin/_sidebar.php
 * Partial sidebar + topbar pembuka untuk semua halaman admin.
 * Variabel $halaman_aktif harus didefinisikan di file pemanggil.
 * Membutuhkan: $pdo (koneksi), session admin aktif (dari auth.php)
 */
$halaman_aktif = $halaman_aktif ?? '';

$pengaturanSidebar = $pdo->query("SELECT nama_website, logo FROM pengaturan_website ORDER BY id ASC LIMIT 1")->fetch();
$nws = $pengaturanSidebar['nama_website'] ?? 'Informatika';
$logoSidebar = !empty($pengaturanSidebar['logo']) ? '../assets/uploads/' . $pengaturanSidebar['logo'] : null;

$menuItems = [
    'dashboard'   => ['dashboard.php', 'bi-speedometer2', 'Dashboard'],
    'pengaturan'  => ['pengaturan.php', 'bi-gear', 'Pengaturan Website'],
    'materi'      => ['materi.php', 'bi-journal-text', 'Materi'],
    'video'       => ['video.php', 'bi-youtube', 'Video'],
    'soal'        => ['soal.php', 'bi-question-circle', 'Soal Evaluasi'],
    'refleksi'    => ['refleksi.php', 'bi-chat-square-text', 'Refleksi Siswa'],
    'nilai'       => ['nilai.php', 'bi-bar-chart', 'Nilai Siswa'],
    'users'       => ['users.php', 'bi-people', 'Manajemen User'],
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars($page_title ?? 'Admin') ?> - <?= htmlspecialchars($nws) ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/datatables.net-bs5@1.13.8/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<!-- ===== Sidebar ===== -->
<aside class="sidebar-admin">
  <div class="sidebar-brand d-flex align-items-center gap-2">
    <?php if ($logoSidebar): ?>
      <img src="<?= htmlspecialchars($logoSidebar) ?>" height="30" alt="Logo">
    <?php else: ?>
      <i class="bi bi-cpu fs-4"></i>
    <?php endif; ?>
    <span><?= htmlspecialchars($nws) ?></span>
  </div>
  <ul class="nav flex-column py-2">
    <?php foreach ($menuItems as $key => [$url, $icon, $label]): ?>
      <li class="nav-item">
        <a class="nav-link d-flex align-items-center gap-2 <?= $halaman_aktif === $key ? 'active' : '' ?>" href="<?= $url ?>">
          <i class="bi <?= $icon ?>"></i> <?= $label ?>
        </a>
      </li>
    <?php endforeach; ?>
    <li class="nav-item mt-2 pt-2" style="border-top:1px solid rgba(255,255,255,0.1);">
      <a class="nav-link d-flex align-items-center gap-2 text-danger-emphasis" href="logout.php" onclick="return confirm('Yakin ingin logout?');">
        <i class="bi bi-box-arrow-right"></i> Logout
      </a>
    </li>
  </ul>
</aside>

<!-- ===== Main Content Wrapper ===== -->
<div class="main-admin-content">
  <div class="admin-topbar d-flex align-items-center justify-content-between">
    <button class="btn btn-light d-lg-none" onclick="toggleSidebar()">
      <i class="bi bi-list fs-4"></i>
    </button>
    <h5 class="fw-bold mb-0 d-none d-lg-block"><?= htmlspecialchars($page_title ?? '') ?></h5>
    <div class="d-flex align-items-center gap-2">
      <div class="icon-circle" style="width:40px;height:40px;font-size:1.1rem;">
        <i class="bi bi-person-circle"></i>
      </div>
      <span class="fw-semibold small d-none d-sm-inline"><?= htmlspecialchars($_SESSION['admin_nama'] ?? 'Admin') ?></span>
    </div>
  </div>
  <div class="p-4">
