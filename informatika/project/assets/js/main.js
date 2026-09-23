/**
 * main.js — Script utama untuk halaman publik
 */

// Toggle sidebar admin (mobile)
function toggleSidebar() {
  const sidebar = document.querySelector('.sidebar-admin');
  if (sidebar) sidebar.classList.toggle('show');
}

// Preview gambar sebelum upload
function previewImage(inputId, previewId) {
  const input = document.getElementById(inputId);
  const preview = document.getElementById(previewId);
  if (!input || !preview) return;
  input.addEventListener('change', function () {
    const file = this.files[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = e => {
        preview.src = e.target.result;
        preview.classList.remove('d-none');
      };
      reader.readAsDataURL(file);
    }
  });
}

document.addEventListener('DOMContentLoaded', function () {
  // Auto init preview pada elemen yang ada di halaman
  previewImage('inputGambar', 'previewGambar');
  previewImage('inputLogo', 'previewLogo');
  previewImage('inputBanner', 'previewBanner');

  // Bootstrap form validation
  const forms = document.querySelectorAll('.needs-validation');
  forms.forEach(form => {
    form.addEventListener('submit', function (event) {
      if (!form.checkValidity()) {
        event.preventDefault();
        event.stopPropagation();
      }
      form.classList.add('was-validated');
    }, false);
  });
});
