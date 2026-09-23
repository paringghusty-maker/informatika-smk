<?php
/**
 * File: praktik.php — Halaman Praktik
 */
require_once __DIR__ . '/includes/koneksi.php';
$page_title = 'Praktik';

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<section class="section-padding">
  <div class="container">
    <div class="text-center mb-5">
      <span class="hero-badge mb-3 d-inline-block">SAATNYA PRAKTIK</span>
      <h2 class="section-title">Praktik Pengolahan Data</h2>
      <p class="section-subtitle">Terapkan materi yang sudah dipelajari ke dalam latihan praktik nyata</p>
    </div>

    <div class="row g-4 justify-content-center">
      <div class="col-lg-8">
        <div class="card-modern p-4 p-md-5">
          <div class="d-flex align-items-center gap-3 mb-4">
            <div class="icon-circle"><i class="bi bi-table"></i></div>
            <div>
              <h5 class="fw-bold mb-1">Praktik dengan Google Sheets</h5>
              <p class="text-muted small mb-0">Gunakan Google Sheets untuk mengolah data latihan</p>
            </div>
          </div>

          <h6 class="fw-semibold mb-2">Instruksi Praktik:</h6>
          <ol class="text-muted mb-4">
            <li class="mb-2">Buka file latihan yang dapat diunduh di bawah ini.</li>
            <li class="mb-2">Salin (copy) data ke Google Sheets milikmu sendiri.</li>
            <li class="mb-2">Gunakan rumus seperti <code>SUM</code>, <code>AVERAGE</code>, dan <code>IF</code> untuk mengolah data nilai.</li>
            <li class="mb-2">Buat grafik sederhana untuk memvisualisasikan hasil pengolahan data.</li>
            <li class="mb-2">Simpan hasil pekerjaanmu, lalu lanjutkan ke tahap Refleksi.</li>
          </ol>

          <div class="d-flex flex-wrap gap-3">
            <a href="https://sheets.google.com" target="_blank" rel="noopener" class="btn btn-success-custom">
              <i class="bi bi-google me-2"></i>Buka Google Sheets
            </a>
            <a href="assets/uploads/latihan-data-siswa.csv" download class="btn btn-hero-outline">
              <i class="bi bi-download me-2"></i>Unduh File Latihan
            </a>
          </div>
          <small class="text-muted d-block mt-3">
            <i class="bi bi-info-circle me-1"></i>
            Jika tombol unduh tidak berfungsi, hubungi admin/guru untuk mendapatkan file latihan terbaru.
          </small>
        </div>
      </div>
    </div>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
