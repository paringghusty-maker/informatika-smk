<?php
/**
 * File: admin/materi.php — CRUD Materi
 */
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/koneksi.php';

$page_title = 'Materi';
$halaman_aktif = 'materi';

$uploadDir = __DIR__ . '/../assets/uploads/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

// ===== Hapus materi =====
if (isset($_GET['hapus'])) {
    $id = (int)$_GET['hapus'];
    $stmt = $pdo->prepare("SELECT gambar FROM materi WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $row = $stmt->fetch();
    if ($row && !empty($row['gambar']) && file_exists($uploadDir . $row['gambar'])) {
        unlink($uploadDir . $row['gambar']);
    }
    $pdo->prepare("DELETE FROM materi WHERE id = :id")->execute([':id' => $id]);
    $_SESSION['flash_success'] = 'Materi berhasil dihapus.';
    header('Location: materi.php');
    exit;
}

// ===== Tambah / Edit materi =====
$editData = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM materi WHERE id = :id");
    $stmt->execute([':id' => (int)$_GET['edit']]);
    $editData = $stmt->fetch();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)($_POST['id'] ?? 0);
    $judul = trim($_POST['judul'] ?? '');
    $deskripsi = trim($_POST['deskripsi'] ?? '');
    $isi = trim($_POST['isi'] ?? '');

    if ($judul === '' || $isi === '') {
        $_SESSION['flash_error'] = 'Judul dan isi materi wajib diisi.';
        header('Location: materi.php' . ($id ? '?edit=' . $id : ''));
        exit;
    }

    $namaGambarBaru = null;
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
        $allowedExt = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
        $ext = strtolower(pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION));
        if (in_array($ext, $allowedExt) && $_FILES['gambar']['size'] <= 3 * 1024 * 1024) {
            $namaGambarBaru = 'materi_' . time() . '_' . uniqid() . '.' . $ext;
            move_uploaded_file($_FILES['gambar']['tmp_name'], $uploadDir . $namaGambarBaru);
        } else {
            $_SESSION['flash_error'] = 'Gambar tidak valid (format JPG/PNG/WEBP/GIF, maks 3MB).';
            header('Location: materi.php' . ($id ? '?edit=' . $id : ''));
            exit;
        }
    }

    if ($id > 0) {
        // UPDATE
        if ($namaGambarBaru) {
            $old = $pdo->prepare("SELECT gambar FROM materi WHERE id = :id");
            $old->execute([':id' => $id]);
            $oldRow = $old->fetch();
            if ($oldRow && !empty($oldRow['gambar']) && file_exists($uploadDir . $oldRow['gambar'])) {
                unlink($uploadDir . $oldRow['gambar']);
            }
            $stmt = $pdo->prepare("UPDATE materi SET judul=:j, deskripsi=:d, isi=:i, gambar=:g WHERE id=:id");
            $stmt->execute([':j' => $judul, ':d' => $deskripsi, ':i' => $isi, ':g' => $namaGambarBaru, ':id' => $id]);
        } else {
            $stmt = $pdo->prepare("UPDATE materi SET judul=:j, deskripsi=:d, isi=:i WHERE id=:id");
            $stmt->execute([':j' => $judul, ':d' => $deskripsi, ':i' => $isi, ':id' => $id]);
        }
        $_SESSION['flash_success'] = 'Materi berhasil diperbarui.';
    } else {
        // INSERT
        $stmt = $pdo->prepare("INSERT INTO materi (judul, deskripsi, isi, gambar) VALUES (:j,:d,:i,:g)");
        $stmt->execute([':j' => $judul, ':d' => $deskripsi, ':i' => $isi, ':g' => $namaGambarBaru]);
        $_SESSION['flash_success'] = 'Materi baru berhasil ditambahkan.';
    }

    header('Location: materi.php');
    exit;
}

$daftarMateri = $pdo->query("SELECT * FROM materi ORDER BY created_at DESC")->fetchAll();

require_once __DIR__ . '/_sidebar.php';
?>

<div class="row g-4">
  <!-- ===== Form Tambah/Edit ===== -->
  <div class="col-lg-5">
    <div class="card-modern p-4">
      <h6 class="fw-bold mb-3">
        <i class="bi <?= $editData ? 'bi-pencil-square' : 'bi-plus-circle' ?> me-2 text-success"></i>
        <?= $editData ? 'Edit Materi' : 'Tambah Materi Baru' ?>
      </h6>
      <form method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
        <input type="hidden" name="id" value="<?= $editData['id'] ?? '' ?>">
        <div class="mb-3">
          <label class="form-label">Judul Materi</label>
          <input type="text" class="form-control" name="judul" required value="<?= htmlspecialchars($editData['judul'] ?? '') ?>">
        </div>
        <div class="mb-3">
          <label class="form-label">Deskripsi Singkat</label>
          <input type="text" class="form-control" name="deskripsi" value="<?= htmlspecialchars($editData['deskripsi'] ?? '') ?>" placeholder="Tampil di card ringkasan beranda">
        </div>
        <div class="mb-3">
          <label class="form-label">Isi Materi Lengkap</label>
          <textarea class="form-control" name="isi" rows="6" required placeholder="Boleh menggunakan tag HTML dasar seperti <p>, <b>, <ul>"><?= htmlspecialchars($editData['isi'] ?? '') ?></textarea>
        </div>
        <div class="mb-3">
          <label class="form-label">Gambar Materi</label>
          <input type="file" class="form-control" id="inputGambar" name="gambar" accept="image/*">
          <?php if (!empty($editData['gambar'])): ?>
            <div class="mt-2"><img src="../assets/uploads/<?= htmlspecialchars($editData['gambar']) ?>" height="60" class="rounded border p-1"></div>
          <?php endif; ?>
          <img id="previewGambar" src="#" alt="Preview" height="60" class="rounded border p-1 d-none mt-2">
        </div>
        <div class="d-flex gap-2">
          <button type="submit" class="btn btn-success-custom flex-fill">
            <i class="bi bi-save me-2"></i><?= $editData ? 'Update Materi' : 'Simpan Materi' ?>
          </button>
          <?php if ($editData): ?>
            <a href="materi.php" class="btn btn-outline-secondary">Batal</a>
          <?php endif; ?>
        </div>
      </form>
    </div>
  </div>

  <!-- ===== Daftar Materi ===== -->
  <div class="col-lg-7">
    <div class="card-modern p-4">
      <h6 class="fw-bold mb-3"><i class="bi bi-list-ul me-2 text-success"></i>Daftar Materi (<?= count($daftarMateri) ?>)</h6>
      <div class="table-responsive">
        <table class="table table-hover align-middle" id="tabelMateri">
          <thead>
            <tr>
              <th>Judul</th>
              <th>Tanggal</th>
              <th class="text-end">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($daftarMateri as $m): ?>
              <tr>
                <td>
                  <div class="fw-semibold"><?= htmlspecialchars($m['judul']) ?></div>
                  <div class="text-muted small text-truncate" style="max-width:220px;"><?= htmlspecialchars($m['deskripsi'] ?? '') ?></div>
                </td>
                <td class="text-nowrap"><?= date('d/m/Y', strtotime($m['created_at'])) ?></td>
                <td class="text-end text-nowrap">
                  <a href="materi.php?edit=<?= (int)$m['id'] ?>" class="btn btn-sm btn-outline-success me-1"><i class="bi bi-pencil"></i></a>
                  <button type="button" class="btn btn-sm btn-outline-danger" onclick="hapusMateri(<?= (int)$m['id'] ?>)"><i class="bi bi-trash"></i></button>
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
function hapusMateri(id) {
  Swal.fire({
    title: 'Hapus materi ini?',
    text: 'Data yang dihapus tidak dapat dikembalikan.',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#dc3545',
    cancelButtonColor: '#6c757d',
    confirmButtonText: 'Ya, hapus',
    cancelButtonText: 'Batal'
  }).then((result) => {
    if (result.isConfirmed) {
      window.location.href = 'materi.php?hapus=' + id;
    }
  });
}
$(document).ready(function () {
  $('#tabelMateri').DataTable({
    language: { search: "Cari:", lengthMenu: "Tampilkan _MENU_ data", info: "Menampilkan _START_ - _END_ dari _TOTAL_ data", paginate: { previous: "Sebelumnya", next: "Berikutnya" }, zeroRecords: "Data tidak ditemukan" }
  });
});
</script>

<?php require_once __DIR__ . '/_footer.php'; ?>
