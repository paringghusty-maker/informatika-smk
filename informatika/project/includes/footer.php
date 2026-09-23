<?php
/**
 * File: includes/footer.php
 * Footer profesional + penutup body/html untuk halaman publik
 */
$tahun = date('Y');
?>
  <footer class="footer-main bg-dark text-light pt-5 pb-4 mt-5">
    <div class="container">
      <div class="row g-4">
        <div class="col-lg-4">
          <h5 class="fw-bold text-success mb-3"><i class="bi bi-cpu me-2"></i><?= htmlspecialchars($nama_website ?? 'Informatika') ?></h5>
          <p class="text-light opacity-75 small mb-0"><?= htmlspecialchars($domain_pelajaran ?? 'Teknologi Informasi dan Analisis Data') ?></p>
        </div>
        <div class="col-lg-4">
          <h6 class="fw-semibold mb-3">Navigasi</h6>
          <ul class="list-unstyled small">
            <li class="mb-2"><a href="index.php" class="footer-link">Beranda</a></li>
            <li class="mb-2"><a href="materi.php" class="footer-link">Materi</a></li>
            <li class="mb-2"><a href="praktik.php" class="footer-link">Praktik</a></li>
            <li class="mb-2"><a href="evaluasi.php" class="footer-link">Evaluasi</a></li>
          </ul>
        </div>
        <div class="col-lg-4">
          <h6 class="fw-semibold mb-3">Tentang</h6>
          <p class="text-light opacity-75 small mb-0">Platform pembelajaran interaktif untuk mendukung siswa memahami Informatika secara mandiri, mudah, dan menyenangkan.</p>
        </div>
      </div>
      <hr class="border-secondary mt-4 mb-3 opacity-25">
      <div class="text-center small text-light opacity-75">
        &copy; <?= $tahun ?> <?= htmlspecialchars($nama_website ?? 'Informatika') ?>. Seluruh hak cipta dilindungi.
      </div>
    </div>
  </footer>

  <!-- Bootstrap JS Bundle -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <!-- Custom JS -->
  <script src="assets/js/main.js"></script>
</body>
</html>
