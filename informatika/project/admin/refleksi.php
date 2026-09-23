<?php
/**
 * File: admin/refleksi.php — Lihat & Kelola Refleksi Siswa
 */
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/koneksi.php';

$page_title = 'Refleksi Siswa';
$halaman_aktif = 'refleksi';

if (isset($_GET['hapus'])) {
    $pdo->prepare("DELETE FROM refleksi WHERE id = :id")->execute([':id' => (int)$_GET['hapus']]);
    $_SESSION['flash_success'] = 'Data refleksi berhasil dihapus.';
    header('Location: refleksi.php');
    exit;
}

$daftarRefleksi = $pdo->query("SELECT * FROM refleksi ORDER BY created_at DESC")->fetchAll();

require_once __DIR__ . '/_sidebar.php';
?>

<div class="card-modern p-4">
  <h6 class="fw-bold mb-3"><i class="bi bi-chat-square-text me-2 text-success"></i>Daftar Refleksi Siswa (<?= count($daftarRefleksi) ?>)</h6>

  <?php if (count($daftarRefleksi) === 0): ?>
    <p class="text-muted small mb-0">Belum ada data refleksi dari siswa.</p>
  <?php else: ?>
    <div class="table-responsive">
      <table class="table table-hover align-middle" id="tabelRefleksi">
        <thead>
          <tr>
            <th>Nama</th>
            <th>Apa yang dipelajari</th>
            <th>Kesulitan</th>
            <th>Tanggal</th>
            <th class="text-end">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($daftarRefleksi as $r): ?>
            <tr>
              <td class="fw-semibold"><?= htmlspecialchars($r['nama']) ?></td>
              <td class="text-truncate" style="max-width:260px;"><?= htmlspecialchars($r['pembelajaran']) ?></td>
              <td class="text-truncate" style="max-width:200px;"><?= htmlspecialchars($r['kesulitan'] ?? '-') ?></td>
              <td class="text-nowrap"><?= date('d/m/Y H:i', strtotime($r['created_at'])) ?></td>
              <td class="text-end">
                <button type="button" class="btn btn-sm btn-outline-success me-1" data-bs-toggle="modal" data-bs-target="#modalDetail<?= (int)$r['id'] ?>"><i class="bi bi-eye"></i></button>
                <button type="button" class="btn btn-sm btn-outline-danger" onclick="hapusRefleksi(<?= (int)$r['id'] ?>)"><i class="bi bi-trash"></i></button>
              </td>
            </tr>

            <!-- Modal Detail -->
            <div class="modal fade" id="modalDetail<?= (int)$r['id'] ?>" tabindex="-1">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <h6 class="modal-title fw-bold"><?= htmlspecialchars($r['nama']) ?></h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                  </div>
                  <div class="modal-body">
                    <p class="fw-semibold mb-1">Apa yang dipelajari hari ini?</p>
                    <p class="text-muted"><?= nl2br(htmlspecialchars($r['pembelajaran'])) ?></p>
                    <p class="fw-semibold mb-1">Kesulitan yang dialami</p>
                    <p class="text-muted mb-0"><?= nl2br(htmlspecialchars($r['kesulitan'] ?: 'Tidak ada kesulitan dituliskan.')) ?></p>
                  </div>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>
</div>

<script>
function hapusRefleksi(id) {
  Swal.fire({
    title: 'Hapus data refleksi ini?',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#dc3545',
    cancelButtonColor: '#6c757d',
    confirmButtonText: 'Ya, hapus',
    cancelButtonText: 'Batal'
  }).then((result) => {
    if (result.isConfirmed) window.location.href = 'refleksi.php?hapus=' + id;
  });
}
$(document).ready(function () {
  $('#tabelRefleksi').DataTable({
    columnDefs: [{ orderable: false, targets: -1 }],
    language: { search: "Cari:", lengthMenu: "Tampilkan _MENU_ data", info: "Menampilkan _START_ - _END_ dari _TOTAL_ data", paginate: { previous: "Sebelumnya", next: "Berikutnya" }, zeroRecords: "Data tidak ditemukan" }
  });
});
</script>

<?php require_once __DIR__ . '/_footer.php'; ?>
