<?php
/**
 * File: evaluasi.php — Soal Evaluasi Pilihan Ganda
 * Alur: isi nama -> kerjakan soal -> submit -> hitung skor -> simpan -> tampilkan hasil
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/includes/koneksi.php';
$page_title = 'Evaluasi';

$daftarSoal = $pdo->query("SELECT * FROM soal ORDER BY id ASC")->fetchAll();
$totalSoal  = count($daftarSoal);

$tahap = 'mulai'; // mulai -> kerjakan -> hasil
$skorAkhir = 0;
$jumlahBenar = 0;
$namaSiswa = '';
$error = '';

// ===== Proses submit nama (mulai mengerjakan) =====
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['aksi']) && $_POST['aksi'] === 'mulai') {
    $nama = trim($_POST['nama_evaluasi'] ?? '');
    if ($nama === '') {
        $error = 'Nama wajib diisi sebelum memulai evaluasi.';
    } else {
        $_SESSION['eval_nama'] = $nama;
        $tahap = 'kerjakan';
    }
}

// ===== Proses submit jawaban (selesai mengerjakan) =====
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['aksi']) && $_POST['aksi'] === 'submit_jawaban') {
    $namaSiswa = $_SESSION['eval_nama'] ?? trim($_POST['nama_evaluasi'] ?? 'Siswa');

    if ($totalSoal > 0) {
        foreach ($daftarSoal as $soal) {
            $jawabanUser = $_POST['soal_' . $soal['id']] ?? null;
            if ($jawabanUser !== null && strtoupper($jawabanUser) === $soal['jawaban_benar']) {
                $jumlahBenar++;
            }
        }
        $skorAkhir = round(($jumlahBenar / $totalSoal) * 100);
    } else {
        $skorAkhir = 0;
    }

    // Simpan hasil ke database
    $stmt = $pdo->prepare("INSERT INTO hasil_nilai (nama, skor) VALUES (:nama, :skor)");
    $stmt->execute([':nama' => $namaSiswa, ':skor' => $skorAkhir]);

    unset($_SESSION['eval_nama']);
    $tahap = 'hasil';
}

// Jika sudah pernah submit nama sebelumnya (refresh halaman di tengah pengerjaan)
if ($tahap === 'mulai' && isset($_SESSION['eval_nama']) && $_SERVER['REQUEST_METHOD'] !== 'POST') {
    $tahap = 'kerjakan';
    $namaSiswa = $_SESSION['eval_nama'];
}

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<section class="section-padding">
  <div class="container">

    <?php if ($tahap === 'mulai'): ?>
      <!-- ============ TAHAP 1: INPUT NAMA ============ -->
      <div class="row justify-content-center">
        <div class="col-lg-6">
          <div class="text-center mb-5">
            <span class="hero-badge mb-3 d-inline-block">EVALUASI AKHIR</span>
            <h2 class="section-title">Sampai mana kemampuanmu anak muda</h2>
            <p class="section-subtitle">Jawab <?= $totalSoal ?> soal pilihan ganda berikut untuk mengukur pemahamanmu</p>
          </div>

          <?php if ($error): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
          <?php endif; ?>

          <?php if ($totalSoal === 0): ?>
            <div class="alert alert-light border text-center text-muted">Belum ada soal evaluasi yang ditambahkan oleh admin.</div>
          <?php else: ?>
            <div class="card-modern p-4 p-md-5">
              <form method="POST">
                <input type="hidden" name="aksi" value="mulai">
                <div class="mb-4">
                  <label for="nama_evaluasi" class="form-label">Masukkan Nama Kamu</label>
                  <input type="text" class="form-control" id="nama_evaluasi" name="nama_evaluasi" placeholder="Nama lengkap" required>
                </div>
                <button type="submit" class="btn btn-success-custom w-100">
                  <i class="bi bi-play-circle me-2"></i>Mulai Evaluasi
                </button>
              </form>
            </div>
          <?php endif; ?>
        </div>
      </div>

    <?php elseif ($tahap === 'kerjakan'): ?>
      <!-- ============ TAHAP 2: KERJAKAN SOAL ============ -->
      <div class="text-center mb-5">
        <span class="hero-badge mb-3 d-inline-block">SEDANG MENGERJAKAN</span>
        <h2 class="section-title">Halo, <?= htmlspecialchars($namaSiswa) ?> 👋</h2>
        <p class="section-subtitle">Jawab seluruh soal di bawah ini, lalu klik "Selesai &amp; Lihat Skor"</p>
      </div>

      <form method="POST" id="formEvaluasi">
        <input type="hidden" name="aksi" value="submit_jawaban">
        <input type="hidden" name="nama_evaluasi" value="<?= htmlspecialchars($namaSiswa) ?>">

        <?php foreach ($daftarSoal as $i => $soal): ?>
          <div class="soal-card">
            <h6 class="fw-bold mb-3">
              <span class="badge text-bg-success-subtle text-success-emphasis me-2"><?= $i + 1 ?></span>
              <?= htmlspecialchars($soal['pertanyaan']) ?>
            </h6>
            <?php foreach (['A' => 'opsi_a', 'B' => 'opsi_b', 'C' => 'opsi_c', 'D' => 'opsi_d'] as $kode => $kolom): ?>
              <label class="option-label">
                <input class="form-check-input me-2" type="radio" name="soal_<?= (int)$soal['id'] ?>" value="<?= $kode ?>" required>
                <span class="option-text"><strong><?= $kode ?>.</strong> <?= htmlspecialchars($soal[$kolom]) ?></span>
              </label>
            <?php endforeach; ?>
          </div>
        <?php endforeach; ?>

        <div class="text-center mt-4">
          <button type="submit" class="btn btn-success-custom px-5">
            <i class="bi bi-check2-circle me-2"></i>Selesai &amp; Lihat Skor
          </button>
        </div>
      </form>

    <?php elseif ($tahap === 'hasil'): ?>
      <!-- ============ TAHAP 3: HASIL SKOR ============ -->
      <div class="row justify-content-center">
        <div class="col-lg-6 text-center">
          <span class="hero-badge mb-3 d-inline-block">HASIL EVALUASI</span>
          <h2 class="section-title mb-4">Kerja Bagus, <?= htmlspecialchars($namaSiswa) ?>!</h2>

          <div class="score-circle mb-4">
            <span class="score-number"><?= $skorAkhir ?></span>
            <span class="small">dari 100</span>
          </div>

          <p class="text-muted mb-4">
            Kamu menjawab benar <strong><?= $jumlahBenar ?></strong> dari <strong><?= $totalSoal ?></strong> soal.
          </p>

          <div class="d-flex justify-content-center gap-3">
            <a href="evaluasi.php" class="btn btn-success-custom"><i class="bi bi-arrow-repeat me-2"></i>Coba Lagi</a>
            <a href="index.php" class="btn btn-hero-outline"><i class="bi bi-house me-2"></i>Kembali ke Beranda</a>
          </div>
        </div>
      </div>
    <?php endif; ?>

  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
