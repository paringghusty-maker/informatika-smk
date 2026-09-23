<?php
/**
 * File: admin/video.php — CRUD Video YouTube
 */
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/koneksi.php';

$page_title = 'Video';
$halaman_aktif = 'video';

/**
 * Konversi berbagai format link YouTube menjadi embed URL
 */
function ke_embed_youtube($url)
{
    $url = trim($url);
    if (preg_match('/(?:youtu\.be\/|youtube\.com\/(?:watch\?v=|embed\/|shorts\/))([A-Za-z0-9_-]{11})/', $url, $m)) {
        return 'https://www.youtube.com/embed/' . $m[1];
    }
    return $url; // fallback: simpan apa adanya jika format tidak dikenali
}

if (isset($_GET['hapus'])) {
    $pdo->prepare("DELETE FROM video WHERE id = :id")->execute([':id' => (int)$_GET['hapus']]);
    $_SESSION['flash_success'] = 'Video berhasil dihapus.';
    header('Location: video.php');
    exit;
}

$editData = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM video WHERE id = :id");
    $stmt->execute([':id' => (int)$_GET['edit']]);
    $editData = $stmt->fetch();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)($_POST['id'] ?? 0);
    $judul = trim($_POST['judul'] ?? '');
    $link  = ke_embed_youtube(trim($_POST['link'] ?? ''));

    if ($judul === '' || $link === '') {
        $_SESSION['flash_error'] = 'Judul dan link video wajib diisi.';
    } elseif ($id > 0) {
        $stmt = $pdo->prepare("UPDATE video SET judul=:j, link=:l WHERE id=:id");
        $stmt->execute([':j' => $judul, ':l' => $link, ':id' => $id]);
        $_SESSION['flash_success'] = 'Video berhasil diperbarui.';
    } else {
        $stmt = $pdo->prepare("INSERT INTO video (judul, link) VALUES (:j, :l)");
        $stmt->execute([':j' => $judul, ':l' => $link]);
        $_SESSION['flash_success'] = 'Video baru berhasil ditambahkan.';
    }
    header('Location: video.php');
    exit;
}

$daftarVideo = $pdo->query("SELECT * FROM video ORDER BY created_at DESC")->fetchAll();

require_once __DIR__ . '/_sidebar.php';
?>

<div class="row g-4">
  <div class="col-lg-5">
    <div class="card-modern p-4">
      <h6 class="fw-bold mb-3">
        <i class="bi <?= $editData ? 'bi-pencil-square' : 'bi-plus-circle' ?> me-2 text-success"></i>
        <?= $editData ? 'Edit Video' : 'Tambah Video Baru' ?>
      </h6>
      <form method="POST" class="needs-validation" novalidate>
        <input type="hidden" name="id" value="<?= $editData['id'] ?? '' ?>">
        <div class="mb-3">
          <label class="form-label">Judul Video</label>
          <input type="text" class="form-control" name="judul" required value="<?= htmlspecialchars($editData['judul'] ?? '') ?>">
        </div>
        <div class="mb-3">
          <label class="form-label">Link YouTube</label>
          <input type="url" class="form-control" name="link" required placeholder="https://www.youtube.com/watch?v=xxxxx" value="<?= htmlspecialchars($editData['link'] ?? '') ?>">
          <small class="text-muted">Tempel link YouTube biasa, sistem akan otomatis mengubahnya ke format embed.</small>
        </div>
        <button type="submit" class="btn btn-success-custom w-100">
          <i class="bi bi-save me-2"></i><?= $editData ? 'Update Video' : 'Simpan Video' ?>
        </button>
        <?php if ($editData): ?>
          <a href="video.php" class="btn btn-outline-secondary w-100 mt-2">Batal</a>
        <?php endif; ?>
      </form>
    </div>
  </div>

  <div class="col-lg-7">
    <div class="card-modern p-4">
      <h6 class="fw-bold mb-3"><i class="bi bi-collection-play me-2 text-success"></i>Daftar Video (<?= count($daftarVideo) ?>)</h6>
      <?php if (count($daftarVideo) === 0): ?>
        <p class="text-muted small mb-0">Belum ada video.</p>
      <?php else: ?>
        <div class="row g-3">
          <?php foreach ($daftarVideo as $v): ?>
            <div class="col-md-6">
              <div class="border rounded-3 p-2">
                <div class="ratio ratio-16x9 rounded-2 overflow-hidden mb-2">
                  <iframe src="<?= htmlspecialchars($v['link']) ?>" allowfullscreen></iframe>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                  <span class="fw-semibold small text-truncate" style="max-width:140px;"><?= htmlspecialchars($v['judul']) ?></span>
                  <div>
                    <a href="video.php?edit=<?= (int)$v['id'] ?>" class="btn btn-sm btn-outline-success"><i class="bi bi-pencil"></i></a>
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="hapusVideo(<?= (int)$v['id'] ?>)"><i class="bi bi-trash"></i></button>
                  </div>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>

<script>
function hapusVideo(id) {
  Swal.fire({
    title: 'Hapus video ini?',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#dc3545',
    cancelButtonColor: '#6c757d',
    confirmButtonText: 'Ya, hapus',
    cancelButtonText: 'Batal'
  }).then((result) => {
    if (result.isConfirmed) window.location.href = 'video.php?hapus=' + id;
  });
}
</script>

<?php require_once __DIR__ . '/_footer.php'; ?>
