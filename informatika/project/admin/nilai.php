<?php
/**
 * File: admin/nilai.php — Lihat & Kelola Nilai Siswa
 */
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/koneksi.php';

$page_title = 'Nilai Siswa';
$halaman_aktif = 'nilai';

if (isset($_GET['hapus'])) {
    $pdo->prepare("DELETE FROM hasil_nilai WHERE id = :id")->execute([':id' => (int)$_GET['hapus']]);
    $_SESSION['flash_success'] = 'Data nilai berhasil dihapus.';
    header('Location: nilai.php');
    exit;
}

$daftarNilai = $pdo->query("SELECT * FROM hasil_nilai ORDER BY created_at DESC")->fetchAll();
$rataRata = $pdo->query("SELECT ROUND(AVG(skor),1) FROM hasil_nilai")->fetchColumn();
$nilaiTertinggi = $pdo->query("SELECT MAX(skor) FROM hasil_nilai")->fetchColumn();
$nilaiTerendah = $pdo->query("SELECT MIN(skor) FROM hasil_nilai")->fetchColumn();

require_once __DIR__ . '/_sidebar.php';
?>

<div class="row g-4 mb-4">
  <div class="col-md-4">
    <div class="stat-card">
      <div class="stat-icon" style="background:#1E8A4C;"><i class="bi bi-graph-up-arrow"></i></div>
      <div>
        <div class="stat-number"><?= $rataRata !== null ? $rataRata : '-' ?></div>
        <div class="stat-label">Rata-rata Nilai</div>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="stat-card">
      <div class="stat-icon" style="background:#0d6efd;"><i class="bi bi-trophy"></i></div>
      <div>
        <div class="stat-number"><?= $nilaiTertinggi !== null ? (int)$nilaiTertinggi : '-' ?></div>
        <div class="stat-label">Nilai Tertinggi</div>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="stat-card">
      <div class="stat-icon" style="background:#dc3545;"><i class="bi bi-graph-down-arrow"></i></div>
      <div>
        <div class="stat-number"><?= $nilaiTerendah !== null ? (int)$nilaiTerendah : '-' ?></div>
        <div class="stat-label">Nilai Terendah</div>
      </div>
    </div>
  </div>
</div>

<div class="card-modern p-4">
  <h6 class="fw-bold mb-3"><i class="bi bi-bar-chart me-2 text-success"></i>Daftar Nilai Siswa (<?= count($daftarNilai) ?>)</h6>

  <?php if (count($daftarNilai) === 0): ?>
    <p class="text-muted small mb-0">Belum ada hasil evaluasi siswa.</p>
  <?php else: ?>
    <div class="table-responsive">
      <table class="table table-hover align-middle" id="tabelNilai">
        <thead>
          <tr>
            <th>Nama</th>
            <th>Skor</th>
            <th>Predikat</th>
            <th>Tanggal</th>
            <th class="text-end">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($daftarNilai as $n):
            $skor = (int)$n['skor'];
            if ($skor >= 90) { $predikat = 'Sangat Baik'; $warna = 'success'; }
            elseif ($skor >= 75) { $predikat = 'Baik'; $warna = 'primary'; }
            elseif ($skor >= 60) { $predikat = 'Cukup'; $warna = 'warning'; }
            else { $predikat = 'Perlu Belajar Lagi'; $warna = 'danger'; }
          ?>
            <tr>
              <td class="fw-semibold"><?= htmlspecialchars($n['nama']) ?></td>
              <td><span class="fw-bold"><?= $skor ?></span></td>
              <td><span class="badge text-bg-<?= $warna ?>-subtle text-<?= $warna ?>-emphasis"><?= $predikat ?></span></td>
              <td class="text-nowrap"><?= date('d/m/Y H:i', strtotime($n['created_at'])) ?></td>
              <td class="text-end">
                <button type="button" class="btn btn-sm btn-outline-danger" onclick="hapusNilai(<?= (int)$n['id'] ?>)"><i class="bi bi-trash"></i></button>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>
</div>

<script>
function hapusNilai(id) {
  Swal.fire({
    title: 'Hapus data nilai ini?',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#dc3545',
    cancelButtonColor: '#6c757d',
    confirmButtonText: 'Ya, hapus',
    cancelButtonText: 'Batal'
  }).then((result) => {
    if (result.isConfirmed) window.location.href = 'nilai.php?hapus=' + id;
  });
}
$(document).ready(function () {
  $('#tabelNilai').DataTable({
    columnDefs: [{ orderable: false, targets: -1 }],
    order: [[3, 'desc']],
    language: { search: "Cari:", lengthMenu: "Tampilkan _MENU_ data", info: "Menampilkan _START_ - _END_ dari _TOTAL_ data", paginate: { previous: "Sebelumnya", next: "Berikutnya" }, zeroRecords: "Data tidak ditemukan" }
  });
});
</script>

<?php require_once __DIR__ . '/_footer.php'; ?>
