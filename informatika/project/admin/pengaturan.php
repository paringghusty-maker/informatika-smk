<?php
/**
 * File: admin/pengaturan.php
 */
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/koneksi.php';

$page_title = 'Pengaturan Website';
$halaman_aktif = 'pengaturan';

$uploadDir = __DIR__ . '/../assets/uploads/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

$pengaturan = $pdo->query("SELECT * FROM pengaturan_website ORDER BY id ASC LIMIT 1")->fetch();

function upload_gambar_setting($fieldName, $uploadDir)
{
    if (!isset($_FILES[$fieldName]) || $_FILES[$fieldName]['error'] === UPLOAD_ERR_NO_FILE) {
        return [null, null]; // tidak ada file baru diupload
    }
    if ($_FILES[$fieldName]['error'] !== UPLOAD_ERR_OK) {
        return [null, 'Terjadi kesalahan saat upload file.'];
    }

    $allowedExt = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
    $ext = strtolower(pathinfo($_FILES[$fieldName]['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, $allowedExt)) {
        return [null, 'Format file tidak didukung. Gunakan JPG, PNG, WEBP, atau GIF.'];
    }
    if ($_FILES[$fieldName]['size'] > 10 * 1024 * 1024) {
        return [null, 'Ukuran file maksimal 10MB.'];
    }

    $namaFile = $fieldName . '_' . time() . '_' . uniqid() . '.' . $ext;
    if (!move_uploaded_file($_FILES[$fieldName]['tmp_name'], $uploadDir . $namaFile)) {
        return [null, 'Gagal menyimpan file ke server.'];
    }
    return [$namaFile, null];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_website  = trim($_POST['nama_website'] ?? '');
    $domain        = trim($_POST['domain'] ?? '');
    $deskripsi     = trim($_POST['deskripsi'] ?? '');
    $pesan_penutup = trim($_POST['pesan_penutup'] ?? '');

    $errors = [];

    [$logoBaru, $errLogo] = upload_gambar_setting('logo', $uploadDir);
    if ($errLogo) $errors[] = $errLogo;

    [$bannerBaru, $errBanner] = upload_gambar_setting('banner', $uploadDir);
    if ($errBanner) $errors[] = $errBanner;

    if ($nama_website === '' || $domain === '') {
        $errors[] = 'Nama website dan domain mata pelajaran wajib diisi.';
    }

    if (empty($errors)) {
        $logoFinal = $logoBaru ?? $pengaturan['logo'];
        $bannerFinal = $bannerBaru ?? $pengaturan['banner'];

        if ($pengaturan) {
            $stmt = $pdo->prepare("UPDATE pengaturan_website SET nama_website=:nw, domain=:dm, logo=:lg, banner=:bn, deskripsi=:ds, pesan_penutup=:pp WHERE id=:id");
            $stmt->execute([
                ':nw' => $nama_website, ':dm' => $domain, ':lg' => $logoFinal,
                ':bn' => $bannerFinal, ':ds' => $deskripsi, ':pp' => $pesan_penutup,
                ':id' => $pengaturan['id'],
            ]);
        } else {
            $stmt = $pdo->prepare("INSERT INTO pengaturan_website (nama_website, domain, logo, banner, deskripsi, pesan_penutup) VALUES (:nw,:dm,:lg,:bn,:ds,:pp)");
            $stmt->execute([
                ':nw' => $nama_website, ':dm' => $domain, ':lg' => $logoFinal,
                ':bn' => $bannerFinal, ':ds' => $deskripsi, ':pp' => $pesan_penutup,
            ]);
        }

        $_SESSION['flash_success'] = 'Pengaturan website berhasil diperbarui.';
        header('Location: pengaturan.php');
        exit;
    } else {
        $_SESSION['flash_error'] = implode(' ', $errors);
    }
}

// reload data terbaru
$pengaturan = $pdo->query("SELECT * FROM pengaturan_website ORDER BY id ASC LIMIT 1")->fetch();

require_once __DIR__ . '/_sidebar.php';
?>

<div class="card-modern p-4 p-md-5" style="max-width:760px;">
  <form method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
    <div class="mb-3">
      <label class="form-label">Nama Website</label>
      <input type="text" class="form-control" name="nama_website" value="<?= htmlspecialchars($pengaturan['nama_website'] ?? '') ?>" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Domain Mata Pelajaran</label>
      <input type="text" class="form-control" name="domain" value="<?= htmlspecialchars($pengaturan['domain'] ?? '') ?>" required>
    </div>

    <div class="row g-3 mb-3">
      <div class="col-md-6">
        <label class="form-label">Logo Website</label>
        <input type="file" class="form-control" id="inputLogo" name="logo" accept="image/*">
        <div class="mt-2">
          <?php if (!empty($pengaturan['logo'])): ?>
            <img src="../assets/uploads/<?= htmlspecialchars($pengaturan['logo']) ?>" height="50" class="rounded border p-1">
          <?php endif; ?>
          <img id="previewLogo" src="#" alt="Preview" height="50" class="rounded border p-1 d-none ms-2">
        </div>
      </div>
      <div class="col-md-6">
        <label class="form-label">Banner Beranda</label>
        <input type="file" class="form-control" id="inputBanner" name="banner" accept="image/*">
        <div class="mt-2">
          <?php if (!empty($pengaturan['banner'])): ?>
            <img src="../assets/uploads/<?= htmlspecialchars($pengaturan['banner']) ?>" height="50" class="rounded border p-1">
          <?php endif; ?>
          <img id="previewBanner" src="#" alt="Preview" height="50" class="rounded border p-1 d-none ms-2">
        </div>
      </div>
    </div>

    <div class="mb-3">
      <label class="form-label">Deskripsi Website</label>
      <textarea class="form-control" name="deskripsi" rows="4"><?= htmlspecialchars($pengaturan['deskripsi'] ?? '') ?></textarea>
    </div>
    <div class="mb-4">
      <label class="form-label">Pesan Penutup</label>
      <textarea class="form-control" name="pesan_penutup" rows="3"><?= htmlspecialchars($pengaturan['pesan_penutup'] ?? '') ?></textarea>
      <small class="text-muted">Pesan ini akan ditampilkan pada bagian penutup halaman beranda.</small>
    </div>

    <button type="submit" class="btn btn-success-custom"><i class="bi bi-save me-2"></i>Simpan Pengaturan</button>
  </form>
</div>

<?php require_once __DIR__ . '/_footer.php'; ?>
