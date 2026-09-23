  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/datatables.net@1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/datatables.net-bs5@1.13.8/js/dataTables.bootstrap5.min.js"></script>
<script src="../assets/js/main.js"></script>

<?php if (!empty($_SESSION['flash_success'])): ?>
<script>
Swal.fire({
  icon: 'success',
  title: 'Berhasil!',
  text: <?= json_encode($_SESSION['flash_success']) ?>,
  confirmButtonColor: '#1E8A4C'
});
</script>
<?php unset($_SESSION['flash_success']); endif; ?>

<?php if (!empty($_SESSION['flash_error'])): ?>
<script>
Swal.fire({
  icon: 'error',
  title: 'Gagal!',
  text: <?= json_encode($_SESSION['flash_error']) ?>,
  confirmButtonColor: '#1E8A4C'
});
</script>
<?php unset($_SESSION['flash_error']); endif; ?>

</body>
</html>
