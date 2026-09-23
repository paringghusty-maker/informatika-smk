<?php
/**
 * File: admin/users.php — Manajemen Akun Pengguna (Admin)
 */
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/koneksi.php';

$page_title = 'Manajemen User';
$halaman_aktif = 'users';

// ===== Hapus user =====
if (isset($_GET['hapus'])) {
    $idHapus = (int)$_GET['hapus'];
    if ($idHapus === (int)$_SESSION['admin_id']) {
        $_SESSION['flash_error'] = 'Tidak dapat menghapus akun yang sedang digunakan.';
    } else {
        $pdo->prepare("DELETE FROM users WHERE id = :id")->execute([':id' => $idHapus]);
        $_SESSION['flash_success'] = 'User berhasil dihapus.';
    }
    header('Location: users.php');
    exit;
}

$editData = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
    $stmt->execute([':id' => (int)$_GET['edit']]);
    $editData = $stmt->fetch();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)($_POST['id'] ?? 0);
    $nama = trim($_POST['nama'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $role = $_POST['role'] ?? 'user';

    if (!in_array($role, ['admin', 'user'])) $role = 'user';

    if ($nama === '' || $username === '') {
        $_SESSION['flash_error'] = 'Nama dan username wajib diisi.';
        header('Location: users.php' . ($id ? '?edit=' . $id : ''));
        exit;
    }

    // Cek username unik
    if ($id > 0) {
        $cek = $pdo->prepare("SELECT id FROM users WHERE username = :u AND id != :id");
        $cek->execute([':u' => $username, ':id' => $id]);
    } else {
        $cek = $pdo->prepare("SELECT id FROM users WHERE username = :u");
        $cek->execute([':u' => $username]);
    }
    if ($cek->fetch()) {
        $_SESSION['flash_error'] = 'Username sudah digunakan, silakan pilih username lain.';
        header('Location: users.php' . ($id ? '?edit=' . $id : ''));
        exit;
    }

    if ($id > 0) {
        if ($password !== '') {
            $hash = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $pdo->prepare("UPDATE users SET nama=:n, username=:u, password=:p, role=:r WHERE id=:id");
            $stmt->execute([':n'=>$nama, ':u'=>$username, ':p'=>$hash, ':r'=>$role, ':id'=>$id]);
        } else {
            $stmt = $pdo->prepare("UPDATE users SET nama=:n, username=:u, role=:r WHERE id=:id");
            $stmt->execute([':n'=>$nama, ':u'=>$username, ':r'=>$role, ':id'=>$id]);
        }
        $_SESSION['flash_success'] = 'Data user berhasil diperbarui.';
    } else {
        if ($password === '') {
            $_SESSION['flash_error'] = 'Password wajib diisi untuk user baru.';
            header('Location: users.php');
            exit;
        }
        $hash = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $pdo->prepare("INSERT INTO users (nama, username, password, role) VALUES (:n,:u,:p,:r)");
        $stmt->execute([':n'=>$nama, ':u'=>$username, ':p'=>$hash, ':r'=>$role]);
        $_SESSION['flash_success'] = 'User baru berhasil ditambahkan.';
    }

    header('Location: users.php');
    exit;
}

$daftarUser = $pdo->query("SELECT * FROM users ORDER BY id ASC")->fetchAll();

require_once __DIR__ . '/_sidebar.php';
?>

<div class="row g-4">
  <div class="col-lg-5">
    <div class="card-modern p-4">
      <h6 class="fw-bold mb-3">
        <i class="bi <?= $editData ? 'bi-pencil-square' : 'bi-person-plus' ?> me-2 text-success"></i>
        <?= $editData ? 'Edit User' : 'Tambah User Baru' ?>
      </h6>
      <form method="POST" class="needs-validation" novalidate>
        <input type="hidden" name="id" value="<?= $editData['id'] ?? '' ?>">
        <div class="mb-3">
          <label class="form-label">Nama Lengkap</label>
          <input type="text" class="form-control" name="nama" required value="<?= htmlspecialchars($editData['nama'] ?? '') ?>">
        </div>
        <div class="mb-3">
          <label class="form-label">Username</label>
          <input type="text" class="form-control" name="username" required value="<?= htmlspecialchars($editData['username'] ?? '') ?>">
        </div>
        <div class="mb-3">
          <label class="form-label">Password <?= $editData ? '(kosongkan jika tidak ingin mengubah)' : '' ?></label>
          <input type="password" class="form-control" name="password" <?= $editData ? '' : 'required' ?> placeholder="<?= $editData ? 'Isi hanya jika ingin reset password' : 'Masukkan password' ?>">
        </div>
        <div class="mb-4">
          <label class="form-label">Role</label>
          <select class="form-select" name="role" required>
            <option value="admin" <?= (isset($editData['role']) && $editData['role'] === 'admin') ? 'selected' : '' ?>>Admin</option>
            <option value="user" <?= (isset($editData['role']) && $editData['role'] === 'user') ? 'selected' : '' ?>>User</option>
          </select>
        </div>
        <div class="d-flex gap-2">
          <button type="submit" class="btn btn-success-custom flex-fill">
            <i class="bi bi-save me-2"></i><?= $editData ? 'Update User' : 'Simpan User' ?>
          </button>
          <?php if ($editData): ?>
            <a href="users.php" class="btn btn-outline-secondary">Batal</a>
          <?php endif; ?>
        </div>
      </form>
    </div>
  </div>

  <div class="col-lg-7">
    <div class="card-modern p-4">
      <h6 class="fw-bold mb-3"><i class="bi bi-people me-2 text-success"></i>Daftar User (<?= count($daftarUser) ?>)</h6>
      <div class="table-responsive">
        <table class="table table-hover align-middle" id="tabelUser">
          <thead>
            <tr>
              <th>Nama</th>
              <th>Username</th>
              <th>Role</th>
              <th class="text-end">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($daftarUser as $u): ?>
              <tr>
                <td class="fw-semibold"><?= htmlspecialchars($u['nama']) ?></td>
                <td><?= htmlspecialchars($u['username']) ?></td>
                <td>
                  <span class="badge text-bg-<?= $u['role'] === 'admin' ? 'success' : 'secondary' ?>-subtle text-<?= $u['role'] === 'admin' ? 'success' : 'secondary' ?>-emphasis">
                    <?= ucfirst($u['role']) ?>
                  </span>
                </td>
                <td class="text-end text-nowrap">
                  <a href="users.php?edit=<?= (int)$u['id'] ?>" class="btn btn-sm btn-outline-success me-1"><i class="bi bi-pencil"></i></a>
                  <?php if ((int)$u['id'] !== (int)$_SESSION['admin_id']): ?>
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="hapusUser(<?= (int)$u['id'] ?>)"><i class="bi bi-trash"></i></button>
                  <?php endif; ?>
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
function hapusUser(id) {
  Swal.fire({
    title: 'Hapus user ini?',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#dc3545',
    cancelButtonColor: '#6c757d',
    confirmButtonText: 'Ya, hapus',
    cancelButtonText: 'Batal'
  }).then((result) => {
    if (result.isConfirmed) window.location.href = 'users.php?hapus=' + id;
  });
}
$(document).ready(function () {
  $('#tabelUser').DataTable({
    columnDefs: [{ orderable: false, targets: -1 }],
    language: { search: "Cari:", lengthMenu: "Tampilkan _MENU_ data", info: "Menampilkan _START_ - _END_ dari _TOTAL_ data", paginate: { previous: "Sebelumnya", next: "Berikutnya" }, zeroRecords: "Data tidak ditemukan" }
  });
});
</script>

<?php require_once __DIR__ . '/_footer.php'; ?>
