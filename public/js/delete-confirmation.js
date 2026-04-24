document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('form[data-confirm-delete="true"]').forEach(function (form) {
        form.addEventListener('submit', function (event) {
            event.preventDefault();

            const title = form.dataset.title || 'Hapus data?';
            const text = form.dataset.text || 'Data yang dihapus tidak dapat dikembalikan.';
            const confirmText = form.dataset.confirmText || 'Ya, hapus';

            Swal.fire({
                title: title,
                text: text,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: confirmText,
                cancelButtonText: 'Batal',
                reverseButtons: true,
                focusCancel: true,
                allowOutsideClick: false,
                allowEscapeKey: true
            }).then(function (result) {
                if (result.isConfirmed) {
                    HTMLFormElement.prototype.submit.call(form);
                }
            });
        });
    });
});
