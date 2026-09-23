<?php
/**
 * File: includes/navbar.php
 * Navbar sticky untuk halaman publik (user)
 * Variabel $nama_website dan $logo_path sudah tersedia dari header.php
 */
$current_page = basename($_SERVER['PHP_SELF']);
?>
<nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top shadow-sm py-3">
  <div class="container">
    <a class="navbar-brand fw-bold d-flex align-items-center gap-2" href="index.php">
      <?php if (!empty($logo_path)): ?>
        <img src="<?= htmlspecialchars($logo_path) ?>" alt="Logo" height="36">
      <?php else: ?>
        <span class="brand-icon"><i class="bi bi-cpu"></i></span>
      <?php endif; ?>
      <span class="text-success"><?= htmlspecialchars($nama_website) ?></span>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="mainNavbar">
      <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-1">
        <li class="nav-item">
          <a class="nav-link <?= $current_page === 'index.php' ? 'active' : '' ?>" href="index.php">Beranda</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?= $current_page === 'materi.php' ? 'active' : '' ?>" href="materi.php">Materi</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?= $current_page === 'praktik.php' ? 'active' : '' ?>" href="praktik.php">Praktik</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?= $current_page === 'refleksi.php' ? 'active' : '' ?>" href="refleksi.php">Refleksi</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?= $current_page === 'evaluasi.php' ? 'active' : '' ?>" href="evaluasi.php">Evaluasi</a>
        </li>
        <li class="nav-item ms-lg-2">
          <a class="btn btn-success rounded-pill px-3" href="admin/login.php"><i class="bi bi-person-lock me-1"></i>Login Admin</a>
        </li>
      </ul>
    </div>
  </div>
</nav>
