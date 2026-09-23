<?php
/**
 * File: refleksi.php — Form Refleksi Siswa
 */
require_once __DIR__ . '/includes/koneksi.php';
$page_title = 'Refleksi';

$berhasil = false;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama'] ?? '');
    $pembelajaran = trim($_POST['pembelajaran'] ?? '');
    $kesulitan = trim($_POST['kesulitan'] ?? '');

    if ($nama === '' || $pembelajaran === '') {
        $error = 'Nama dan kolom "Apa yang dipelajari hari ini?" wajib diisi.';
    } else {
        $stmt = $pdo->prepare("INSERT INTO refleksi (nama, pembelajaran, kesulitan) VALUES (:nama, :pembelajaran, :kesulitan)");
        $stmt->execute([
            ':nama' => $nama,
            ':pembelajaran' => $pembelajaran,
            ':kesulitan' => $kesulitan !== '' ? $kesulitan : null,
        ]);
        $berhasil = true;
    }
}

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<section class="section-padding">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-7">
        <div class="text-center mb-5">
          <span class="hero-badge mb-3 d-inline-block">REFLEKSI DIRI</span>
          <h2 class="section-title">Refleksi Pembelajaran</h2>
          <p class="section-subtitle">Ceritakan pengalaman belajarmu hari ini</p>
        </div>

        <?php if ($berhasil): ?>
          <div class="alert alert-success d-flex align-items-center gap-2">
            <i class="bi bi-check-circle-fill fs-5"></i>
            <div>Terima kasih! Refleksimu berhasil disimpan.</div>
          </div>
        <?php endif; ?>
        <?php if ($error): ?>
          <div class="alert alert-danger d-flex align-items-center gap-2">
            <i class="bi bi-exclamation-triangle-fill fs-5"></i>
            <div><?= htmlspecialchars($error) ?></div>
          </div>
        <?php endif; ?>

        <div class="card-modern p-4 p-md-5">
          <form method="POST" class="needs-validation" novalidate>
            <div class="mb-3">
              <label for="nama" class="form-label">Nama Siswa</label>
              <input type="text" class="form-control" id="nama" name="nama" placeholder="Masukkan nama lengkap" required value="<?= htmlspecialchars($_POST['nama'] ?? '') ?>">
              <div class="invalid-feedback">Nama wajib diisi.</div>
            </div>
            <div class="mb-3">
              <label for="pembelajaran" class="form-label">Apa yang dipelajari hari ini?</label>
              <textarea class="form-control" id="pembelajaran" name="pembelajaran" rows="4" placeholder="Tuliskan hal yang telah kamu pelajari..." required><?= htmlspecialchars($_POST['pembelajaran'] ?? '') ?></textarea>
              <div class="invalid-feedback">Kolom ini wajib diisi.</div>
            </div>
            <div class="mb-4">
              <label for="kesulitan" class="form-label">Kesulitan yang dialami</label>
              <textarea class="form-control" id="kesulitan" name="kesulitan" rows="4" placeholder="Tuliskan kesulitan yang kamu alami (opsional)"><?= htmlspecialchars($_POST['kesulitan'] ?? '') ?></textarea>
            </div>
            <button type="submit" class="btn btn-success-custom w-100">
              <i class="bi bi-send-check me-2"></i>Kirim Refleksi
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
