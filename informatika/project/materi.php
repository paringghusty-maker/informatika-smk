<?php
/**
 * File: materi.php — Halaman Materi & Video Pembelajaran
 */
require_once __DIR__ . '/includes/koneksi.php';
$page_title = 'Materi';

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';

$daftarMateri = $pdo->query("SELECT * FROM materi ORDER BY created_at DESC")->fetchAll();
$daftarVideo  = $pdo->query("SELECT * FROM video ORDER BY created_at DESC")->fetchAll();
?>

<section class="section-padding">
  <div class="container">
    <div class="text-center mb-5">
      <span class="hero-badge mb-3 d-inline-block">MATERI PEMBELAJARAN</span>
      <h2 class="section-title">Materi Informatika</h2>
      <p class="section-subtitle">Pelajari materi <?= htmlspecialchars($domain_pelajaran) ?> secara bertahap</p>
    </div>

    <?php if (count($daftarMateri) === 0): ?>
      <div class="alert alert-light border text-center text-muted">Belum ada materi yang ditambahkan oleh admin.</div>
    <?php else: ?>
      <?php foreach ($daftarMateri as $i => $m): ?>
        <div id="materi-<?= (int)$m['id'] ?>" class="card-modern p-4 p-md-5 mb-4">
          <div class="row g-4 align-items-start">
            <?php if (!empty($m['gambar'])): ?>
              <div class="col-md-4">
                <img src="assets/uploads/<?= htmlspecialchars($m['gambar']) ?>" alt="<?= htmlspecialchars($m['judul']) ?>" class="img-fluid rounded-3 w-100" style="object-fit:cover; max-height:220px;">
              </div>
              <div class="col-md-8">
            <?php else: ?>
              <div class="col-12">
            <?php endif; ?>
                <div class="d-flex align-items-center gap-2 mb-2">
                  <span class="badge rounded-pill text-bg-success-subtle text-success-emphasis">Materi <?= $i + 1 ?></span>
                  <small class="text-muted"><i class="bi bi-calendar3 me-1"></i><?= date('d M Y', strtotime($m['created_at'])) ?></small>
                </div>
                <h4 class="fw-bold mb-2"><?= htmlspecialchars($m['judul']) ?></h4>
                <?php if (!empty($m['deskripsi'])): ?>
                  <p class="text-muted mb-3"><?= htmlspecialchars($m['deskripsi']) ?></p>
                <?php endif; ?>
                <div class="materi-isi">
                  <?= $m['isi'] // konten HTML dari admin, dipercaya berasal dari admin terverifikasi ?>
                </div>
              </div>
          </div>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>
</section>

<!-- ============ VIDEO PEMBELAJARAN ============ -->
<section class="section-padding bg-soft">
  <div class="container">
    <div class="text-center mb-5">
      <span class="hero-badge mb-3 d-inline-block">VIDEO</span>
      <h2 class="section-title">Video Pembelajaran</h2>
      <p class="section-subtitle">Tonton video berikut untuk memperkuat pemahamanmu</p>
    </div>

    <?php if (count($daftarVideo) === 0): ?>
      <div class="alert alert-light border text-center text-muted">Belum ada video yang ditambahkan oleh admin.</div>
    <?php else: ?>
      <div class="row g-4">
        <?php foreach ($daftarVideo as $v): ?>
          <div class="col-lg-6">
            <div class="card-modern p-3">
              <div class="ratio ratio-16x9 rounded-3 overflow-hidden mb-3">
                <iframe src="<?= htmlspecialchars($v['link']) ?>" title="<?= htmlspecialchars($v['judul']) ?>" allowfullscreen></iframe>
              </div>
              <h6 class="fw-bold px-2 mb-2"><?= htmlspecialchars($v['judul']) ?></h6>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
