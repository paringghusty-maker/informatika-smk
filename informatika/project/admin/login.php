<?php
/**
 * File: admin/login.php
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../includes/koneksi.php';

// Jika sudah login, langsung ke dashboard
if (isset($_SESSION['admin_id']) && ($_SESSION['admin_role'] ?? '') === 'admin') {
    header('Location: dashboard.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $error = 'Username dan password wajib diisi.';
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username AND role = 'admin' LIMIT 1");
        $stmt->execute([':username' => $username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['admin_id']   = $user['id'];
            $_SESSION['admin_nama'] = $user['nama'];
            $_SESSION['admin_role'] = $user['role'];
            header('Location: dashboard.php');
            exit;
        } else {
            $error = 'Username atau password salah.';
        }
    }
}

// Ambil nama website untuk branding halaman login
$pengaturan = $pdo->query("SELECT nama_website, logo FROM pengaturan_website ORDER BY id ASC LIMIT 1")->fetch();
$nama_website = $pengaturan['nama_website'] ?? 'Informatika';
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login Admin - <?= htmlspecialchars($nama_website) ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<div class="login-admin-wrapper">
  <div class="login-card">
    <div class="text-center mb-4">
      <div class="icon-circle mx-auto mb-3" style="width:64px;height:64px;font-size:1.8rem;">
        <i class="bi bi-shield-lock"></i>
      </div>
      <h4 class="fw-bold mb-1">Login Admin</h4>
      <p class="text-muted small mb-0"><?= htmlspecialchars($nama_website) ?></p>
    </div>

    <?php if ($error): ?>
      <div class="alert alert-danger small d-flex align-items-center gap-2">
        <i class="bi bi-exclamation-triangle-fill"></i>
        <div><?= htmlspecialchars($error) ?></div>
      </div>
    <?php endif; ?>

    <form method="POST">
      <div class="mb-3">
        <label class="form-label">Username</label>
        <div class="input-group">
          <span class="input-group-text bg-white"><i class="bi bi-person"></i></span>
          <input type="text" class="form-control" name="username" placeholder="Masukkan username" required autofocus>
        </div>
      </div>
      <div class="mb-4">
        <label class="form-label">Password</label>
        <div class="input-group">
          <span class="input-group-text bg-white"><i class="bi bi-lock"></i></span>
          <input type="password" class="form-control" name="password" placeholder="Masukkan password" required>
        </div>
      </div>
      <button type="submit" class="btn btn-success-custom w-100">
        <i class="bi bi-box-arrow-in-right me-2"></i>Masuk
      </button>
    </form>

    <div class="text-center mt-4">
      <a href="../index.php" class="text-muted small"><i class="bi bi-arrow-left me-1"></i>Kembali ke Beranda</a>
    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
