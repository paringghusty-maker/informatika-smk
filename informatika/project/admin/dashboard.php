<?php
/**
 * File: admin/dashboard.php
 */
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/koneksi.php';

$page_title = 'Dashboard';
$halaman_aktif = 'dashboard';

$jmlMateri   = $pdo->query("SELECT COUNT(*) FROM materi")->fetchColumn();
$jmlVideo    = $pdo->query("SELECT COUNT(*) FROM video")->fetchColumn();
$jmlSoal     = $pdo->query("SELECT COUNT(*) FROM soal")->fetchColumn();
$jmlRefleksi = $pdo->query("SELECT COUNT(*) FROM refleksi")->fetchColumn();
$jmlNilai    = $pdo->query("SELECT COUNT(*) FROM hasil_nilai")->fetchColumn();
$rataNilai   = $pdo->query("SELECT ROUND(AVG(skor),1) FROM hasil_nilai")->fetchColumn();

$refleksiTerbaru = $pdo->query("SELECT * FROM refleksi ORDER BY created_at DESC LIMIT 5")->fetchAll();
$nilaiTerbaru    = $pdo->query("SELECT * FROM hasil_nilai ORDER BY created_at DESC LIMIT 5")->fetchAll();

require_once __DIR__ . '/_sidebar.php';
?>

<div class="mb-4 d-lg-none">
  <h4 class="fw-bold mb-0">Dashboard</h4>
  <p class="text-muted small mb-0">Selamat datang kembali, <?= htmlspecialchars($_SESSION['admin_nama'] ?? 'Admin') ?>!</p>
</div>
<div class="mb-4 d-none d-lg-block">
  <p class="text-muted mb-0">Selamat datang kembali, <strong><?= htmlspecialchars($_SESSION['admin_nama'] ?? 'Admin') ?></strong>! Berikut ringkasan aktivitas website.</p>
</div>

<div class="row g-4 mb-4">
  <div class="col-md-6 col-xl-3">
    <div class="stat-card">
      <div class="stat-icon" style="background:#1E8A4C;"><i class="bi bi-journal-text"></i></div>
      <div>
        <div class="stat-number"><?= (int)$jmlMateri ?></div>
        <div class="stat-label">Jumlah Materi</div>
      </div>
    </div>
  </div>
  <div class="col-md-6 col-xl-3">
    <div class="stat-card">
      <div class="stat-icon" style="background:#dc3545;"><i class="bi bi-youtube"></i></div>
      <div>
        <div class="stat-number"><?= (int)$jmlVideo ?></div>
        <div class="stat-label">Jumlah Video</div>
      </div>
    </div>
  </div>
  <div class="col-md-6 col-xl-3">
    <div class="stat-card">
      <div class="stat-icon" style="background:#fd7e14;"><i class="bi bi-question-circle"></i></div>
      <div>
        <div class="stat-number"><?= (int)$jmlSoal ?></div>
        <div class="stat-label">Jumlah Soal</div>
      </div>
    </div>
  </div>
  <div class="col-md-6 col-xl-3">
    <div class="stat-card">
      <div class="stat-icon" style="background:#0d6efd;"><i class="bi bi-chat-square-text"></i></div>
      <div>
        <div class="stat-number"><?= (int)$jmlRefleksi ?></div>
        <div class="stat-label">Jumlah Refleksi</div>
      </div>
    </div>
  </div>
  <div class="col-md-6 col-xl-3">
    <div class="stat-card">
      <div class="stat-icon" style="background:#6610f2;"><i class="bi bi-bar-chart"></i></div>
      <div>
        <div class="stat-number"><?= (int)$jmlNilai ?></div>
        <div class="stat-label">Jumlah Hasil Evaluasi</div>
      </div>
    </div>
  </div>
  <div class="col-md-6 col-xl-3">
    <div class="stat-card">
      <div class="stat-icon" style="background:#20c997;"><i class="bi bi-graph-up-arrow"></i></div>
      <div>
        <div class="stat-number"><?= $rataNilai !== null ? $rataNilai : '-' ?></div>
        <div class="stat-label">Rata-rata Nilai</div>
      </div>
    </div>
  </div>
</div>

<div class="row g-4">
  <div class="col-lg-6">
    <div class="card-modern p-4">
      <h6 class="fw-bold mb-3"><i class="bi bi-chat-square-text me-2 text-success"></i>Refleksi Terbaru</h6>
      <?php if (count($refleksiTerbaru) === 0): ?>
        <p class="text-muted small mb-0">Belum ada data refleksi.</p>
      <?php else: ?>
        <ul class="list-group list-group-flush">
          <?php foreach ($refleksiTerbaru as $r): ?>
            <li class="list-group-item px-0 d-flex justify-content-between align-items-start">
              <div>
                <div class="fw-semibold"><?= htmlspecialchars($r['nama']) ?></div>
                <div class="text-muted small text-truncate" style="max-width:280px;"><?= htmlspecialchars($r['pembelajaran']) ?></div>
              </div>
              <small class="text-muted"><?= date('d/m/Y', strtotime($r['created_at'])) ?></small>
            </li>
          <?php endforeach; ?>
        </ul>
      <?php endif; ?>
    </div>
  </div>
  <div class="col-lg-6">
    <div class="card-modern p-4">
      <h6 class="fw-bold mb-3"><i class="bi bi-bar-chart me-2 text-success"></i>Nilai Terbaru</h6>
      <?php if (count($nilaiTerbaru) === 0): ?>
        <p class="text-muted small mb-0">Belum ada data nilai.</p>
      <?php else: ?>
        <ul class="list-group list-group-flush">
          <?php foreach ($nilaiTerbaru as $n): ?>
            <li class="list-group-item px-0 d-flex justify-content-between align-items-center">
              <div class="fw-semibold"><?= htmlspecialchars($n['nama']) ?></div>
              <span class="badge rounded-pill text-bg-success-subtle text-success-emphasis"><?= (int)$n['skor'] ?> poin</span>
            </li>
          <?php endforeach; ?>
        </ul>
      <?php endif; ?>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/_footer.php'; ?>
