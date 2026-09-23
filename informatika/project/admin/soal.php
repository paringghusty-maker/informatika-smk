<?php
/**
 * File: admin/soal.php — CRUD Soal Evaluasi
 */
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/koneksi.php';

$page_title = 'Soal Evaluasi';
$halaman_aktif = 'soal';

if (isset($_GET['hapus'])) {
    $pdo->prepare("DELETE FROM soal WHERE id = :id")->execute([':id' => (int)$_GET['hapus']]);
    $_SESSION['flash_success'] = 'Soal berhasil dihapus.';
    header('Location: soal.php');
    exit;
}

$editData = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM soal WHERE id = :id");
    $stmt->execute([':id' => (int)$_GET['edit']]);
    $editData = $stmt->fetch();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)($_POST['id'] ?? 0);
    $pertanyaan = trim($_POST['pertanyaan'] ?? '');
    $opsi_a = trim($_POST['opsi_a'] ?? '');
    $opsi_b = trim($_POST['opsi_b'] ?? '');
    $opsi_c = trim($_POST['opsi_c'] ?? '');
    $opsi_d = trim($_POST['opsi_d'] ?? '');
    $jawaban_benar = strtoupper(trim($_POST['jawaban_benar'] ?? ''));

    if ($pertanyaan === '' || $opsi_a === '' || $opsi_b === '' || $opsi_c === '' || $opsi_d === '' || !in_array($jawaban_benar, ['A','B','C','D'])) {
        $_SESSION['flash_error'] = 'Seluruh kolom soal wajib diisi dan jawaban benar harus dipilih.';
        header('Location: soal.php' . ($id ? '?edit=' . $id : ''));
        exit;
    }

    if ($id > 0) {
        $stmt = $pdo->prepare("UPDATE soal SET pertanyaan=:p, opsi_a=:a, opsi_b=:b, opsi_c=:c, opsi_d=:d, jawaban_benar=:jb WHERE id=:id");
        $stmt->execute([':p'=>$pertanyaan, ':a'=>$opsi_a, ':b'=>$opsi_b, ':c'=>$opsi_c, ':d'=>$opsi_d, ':jb'=>$jawaban_benar, ':id'=>$id]);
        $_SESSION['flash_success'] = 'Soal berhasil diperbarui.';
    } else {
        $stmt = $pdo->prepare("INSERT INTO soal (pertanyaan, opsi_a, opsi_b, opsi_c, opsi_d, jawaban_benar) VALUES (:p,:a,:b,:c,:d,:jb)");
        $stmt->execute([':p'=>$pertanyaan, ':a'=>$opsi_a, ':b'=>$opsi_b, ':c'=>$opsi_c, ':d'=>$opsi_d, ':jb'=>$jawaban_benar]);
        $_SESSION['flash_success'] = 'Soal baru berhasil ditambahkan.';
    }
    header('Location: soal.php');
    exit;
}

$daftarSoal = $pdo->query("SELECT * FROM soal ORDER BY id ASC")->fetchAll();

require_once __DIR__ . '/_sidebar.php';
?>

<div class="row g-4">
  <div class="col-lg-5">
    <div class="card-modern p-4">
      <h6 class="fw-bold mb-3">
        <i class="bi <?= $editData ? 'bi-pencil-square' : 'bi-plus-circle' ?> me-2 text-success"></i>
        <?= $editData ? 'Edit Soal' : 'Tambah Soal Baru' ?>
      </h6>
      <form method="POST" class="needs-validation" novalidate>
        <input type="hidden" name="id" value="<?= $editData['id'] ?? '' ?>">
        <div class="mb-3">
          <label class="form-label">Pertanyaan</label>
          <textarea class="form-control" name="pertanyaan" rows="2" required><?= htmlspecialchars($editData['pertanyaan'] ?? '') ?></textarea>
        </div>
        <div class="mb-2">
          <label class="form-label">Opsi A</label>
          <input type="text" class="form-control" name="opsi_a" required value="<?= htmlspecialchars($editData['opsi_a'] ?? '') ?>">
        </div>
        <div class="mb-2">
          <label class="form-label">Opsi B</label>
          <input type="text" class="form-control" name="opsi_b" required value="<?= htmlspecialchars($editData['opsi_b'] ?? '') ?>">
        </div>
        <div class="mb-2">
          <label class="form-label">Opsi C</label>
          <input type="text" class="form-control" name="opsi_c" required value="<?= htmlspecialchars($editData['opsi_c'] ?? '') ?>">
        </div>
        <div class="mb-3">
          <label class="form-label">Opsi D</label>
          <input type="text" class="form-control" name="opsi_d" required value="<?= htmlspecialchars($editData['opsi_d'] ?? '') ?>">
        </div>
        <div class="mb-3">
          <label class="form-label">Jawaban Benar</label>
          <select class="form-select" name="jawaban_benar" required>
            <option value="">-- Pilih jawaban benar --</option>
            <?php foreach (['A','B','C','D'] as $opt): ?>
              <option value="<?= $opt ?>" <?= (isset($editData['jawaban_benar']) && $editData['jawaban_benar'] === $opt) ? 'selected' : '' ?>>Opsi <?= $opt ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="d-flex gap-2">
          <button type="submit" class="btn btn-success-custom flex-fill">
            <i class="bi bi-save me-2"></i><?= $editData ? 'Update Soal' : 'Simpan Soal' ?>
          </button>
          <?php if ($editData): ?>
            <a href="soal.php" class="btn btn-outline-secondary">Batal</a>
          <?php endif; ?>
        </div>
      </form>
    </div>
  </div>

  <div class="col-lg-7">
    <div class="card-modern p-4">
      <h6 class="fw-bold mb-3"><i class="bi bi-list-ol me-2 text-success"></i>Daftar Soal (<?= count($daftarSoal) ?>)</h6>
      <div class="table-responsive">
        <table class="table table-hover align-middle" id="tabelSoal">
          <thead>
            <tr>
              <th>Pertanyaan</th>
              <th>Jawaban</th>
              <th class="text-end">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($daftarSoal as $s): ?>
              <tr>
                <td class="text-truncate" style="max-width:280px;"><?= htmlspecialchars($s['pertanyaan']) ?></td>
                <td><span class="badge text-bg-success-subtle text-success-emphasis">Opsi <?= htmlspecialchars($s['jawaban_benar']) ?></span></td>
                <td class="text-end text-nowrap">
                  <a href="soal.php?edit=<?= (int)$s['id'] ?>" class="btn btn-sm btn-outline-success me-1"><i class="bi bi-pencil"></i></a>
                  <button type="button" class="btn btn-sm btn-outline-danger" onclick="hapusSoal(<?= (int)$s['id'] ?>)"><i class="bi bi-trash"></i></button>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<script>
function hapusSoal(id) {
  Swal.fire({
    title: 'Hapus soal ini?',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#dc3545',
    cancelButtonColor: '#6c757d',
    confirmButtonText: 'Ya, hapus',
    cancelButtonText: 'Batal'
  }).then((result) => {
    if (result.isConfirmed) window.location.href = 'soal.php?hapus=' + id;
  });
}
$(document).ready(function () {
  $('#tabelSoal').DataTable({
    language: { search: "Cari:", lengthMenu: "Tampilkan _MENU_ data", info: "Menampilkan _START_ - _END_ dari _TOTAL_ data", paginate: { previous: "Sebelumnya", next: "Berikutnya" }, zeroRecords: "Data tidak ditemukan" }
  });
});
</script>

<?php require_once __DIR__ . '/_footer.php'; ?>
