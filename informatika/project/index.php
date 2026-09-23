<?php
/**
 * File: index.php — Beranda
 */
require_once __DIR__ . '/includes/koneksi.php';

$page_title = 'Beranda';

// Ambil pengaturan untuk hero section (banner & deskripsi tambahan)
$stmt = $pdo->query("SELECT * FROM pengaturan_website ORDER BY id ASC LIMIT 1");
$pengaturan = $stmt->fetch();
$banner_path = !empty($pengaturan['banner']) ? 'assets/uploads/' . $pengaturan['banner'] : null;

// Statistik singkat (opsional ditampilkan di beranda)
$jmlMateri = $pdo->query("SELECT COUNT(*) FROM materi")->fetchColumn();
$jmlVideo  = $pdo->query("SELECT COUNT(*) FROM video")->fetchColumn();
$jmlSoal   = $pdo->query("SELECT COUNT(*) FROM soal")->fetchColumn();

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<!-- ============ HERO SECTION ============ -->
<section class="hero-section">
  <div class="container">
    <div class="row align-items-center g-5">
      <div class="col-lg-6">
        <span class="hero-badge mb-3 d-inline-block">PEMBELAJARAN INTERAKTIF</span>
        <h1 class="hero-title mb-3"><?= htmlspecialchars($nama_website) ?></h1>
        <p class="hero-subtitle mb-4"><?= htmlspecialchars($domain_pelajaran) ?></p>
        <?php if (!empty($pengaturan['deskripsi'])): ?>
          <p class="text-secondary mb-4"><?= nl2br(htmlspecialchars($pengaturan['deskripsi'])) ?></p>
        <?php endif; ?>
        <div class="d-flex flex-wrap gap-3">
          <a href="materi.php" class="btn btn-hero-primary">
            <i class="bi bi-rocket-takeoff me-2"></i>Mulai Belajar
          </a>
          <a href="materi.php" class="btn btn-hero-outline">
            <i class="bi bi-journal-text me-2"></i>Lihat Materi
          </a>
        </div>
        <div class="d-flex gap-4 mt-5">
          <div>
            <h4 class="fw-bold text-success mb-0"><?= (int)$jmlMateri ?>+</h4>
            <small class="text-muted">Materi</small>
          </div>
          <div>
            <h4 class="fw-bold text-success mb-0"><?= (int)$jmlVideo ?>+</h4>
            <small class="text-muted">Video</small>
            </div>
          <div>
            <h4 class="fw-bold text-success mb-0"><?= (int)$jmlSoal ?>+</h4>
            <small class="text-muted">Soal Evaluasi</small>
          </div>
        </div>
      </div>
      <div class="col-lg-6">
        <?php if ($banner_path): ?>
          <img src="<?= htmlspecialchars($banner_path) ?>" alt="Banner" class="img-fluid rounded-4 shadow-lg">
        <?php else: ?>
          <div class="hero-visual">
            <i class="bi bi-bar-chart-line"></i>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>

<!-- ============ PETUNJUK PENGGUNAAN ============ -->
<section class="section-padding bg-soft">
  <div class="container">
    <div class="text-center">
      <h2 class="section-title">Petunjuk Penggunaan</h2>
      <p class="section-subtitle">Ikuti langkah berikut untuk memulai pembelajaran</p>
    </div>
    <div class="row g-4">
      <div class="col-md-3 col-6">
        <div class="step-box">
          <div class="step-number">1</div>
          <h6 class="fw-bold mb-2">Baca Materi</h6>
          <p class="text-muted small mb-0">Pelajari konsep dasar pada halaman materi.</p>
        </div>
      </div>
      <div class="col-md-3 col-6">
        <div class="step-box">
          <div class="step-number">2</div>
          <h6 class="fw-bold mb-2">Tonton Video</h6>
          <p class="text-muted small mb-0">Perkuat pemahaman lewat video pembelajaran.</p>
        </div>
      </div>
      <div class="col-md-3 col-6">
        <div class="step-box">
          <div class="step-number">3</div>
          <h6 class="fw-bold mb-2">Lakukan Praktik</h6>
          <p class="text-muted small mb-0">Terapkan ilmu melalui latihan praktik langsung.</p>
        </div>
      </div>
      <div class="col-md-3 col-6">
        <div class="step-box">
          <div class="step-number">4</div>
          <h6 class="fw-bold mb-2">Kerjakan Evaluasi</h6>
          <p class="text-muted small mb-0">Uji pemahamanmu lewat soal evaluasi.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ============ MASALAH AWAL ============ -->
<section class="section-padding">
  <div class="container">
    <div class="row align-items-center g-5">
      <div class="col-lg-6">
        <span class="hero-badge mb-3 d-inline-block">STUDI KASUS</span>
        <h2 class="section-title">Masalah Awal</h2>
        <p class="text-muted">Sebelum belajar lebih jauh, mari kita renungkan permasalahan berikut yang sering terjadi di lingkungan sekolah.</p>
      </div>
      <div class="col-lg-6">
        <div class="case-card">
          <p class="case-quote mb-4">
            "Seorang guru masih menghitung nilai siswa secara manual sehingga membutuhkan waktu lama dan sering terjadi kesalahan."
          </p>
          <div class="case-question">
            <i class="bi bi-question-circle me-2"></i>
            Bagaimana cara mengolah data dengan lebih cepat dan efisien?
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ============ RINGKASAN MATERI TERBARU ============ -->
<section class="section-padding bg-soft">
  <div class="container">
    <div class="text-center">
      <h2 class="section-title">Materi Terbaru</h2>
      <p class="section-subtitle">Beberapa materi pilihan untuk memulai pembelajaranmu</p>
    </div>
    <div class="row g-4">
      <?php
      $materiTerbaru = $pdo->query("SELECT * FROM materi ORDER BY created_at DESC LIMIT 3")->fetchAll();
      if (count($materiTerbaru) === 0):
      ?>
        <div class="col-12 text-center text-muted">Belum ada materi yang tersedia.</div>
      <?php else: foreach ($materiTerbaru as $m): ?>
        <div class="col-md-4">
          <div class="card-modern">
            <?php if (!empty($m['gambar'])): ?>
              <img src="assets/uploads/<?= htmlspecialchars($m['gambar']) ?>" class="card-img-top" alt="<?= htmlspecialchars($m['judul']) ?>">
            <?php else: ?>
              <div class="card-img-placeholder"><i class="bi bi-journal-richtext"></i></div>
            <?php endif; ?>
            <div class="p-4">
              <h5 class="fw-bold mb-2"><?= htmlspecialchars($m['judul']) ?></h5>
              <p class="text-muted small mb-3"><?= htmlspecialchars($m['deskripsi'] ?? '') ?></p>
              <a href="materi.php#materi-<?= (int)$m['id'] ?>" class="btn btn-sm btn-success-custom">Baca Selengkapnya</a>
            </div>
          </div>
        </div>
      <?php endforeach; endif; ?>
    </div>
  </div>
</section>

<!-- ============ PENUTUP ============ -->
<section class="section-padding">
  <div class="container">
    <div class="closing-section">
      <i class="bi bi-stars fs-1 mb-3 d-block"></i>
      <h3 class="fw-bold mb-2">Pesan untuk Kamu</h3>
      <p class="mb-0 fs-5">
        <?= htmlspecialchars($pengaturan['pesan_penutup'] ?? 'Belajarlah dengan tekun, karena ilmu yang bermanfaat akan selalu membuka jalan menuju masa depan yang lebih baik.') ?>
      </p>
    </div>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
