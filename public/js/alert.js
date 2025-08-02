// public/js/index-table.js
function showSuccess(message, texts = window.APP_TEXTS) {
    Swal.fire({
        icon: 'success',
        title: texts.successTitle,
        text: message || texts.successMessage,
        timer: 1800,
        showConfirmButton: false
    });
}

function showError(message, texts = window.APP_TEXTS) {
    Swal.fire({
        icon: 'error',
        title: texts.errorTitle,
        text: message || texts.errorMessage,
    });
}

function showConfirm(message, callback, texts = window.APP_TEXTS) {
    Swal.fire({
        icon: 'question',
        title: texts.confirmTitle,
        text: message || texts.confirmMessage,
        showCancelButton: true,
        confirmButtonText: texts.confirm,
        cancelButtonText: texts.cancel
    }).then((result) => {
        if (result.isConfirmed) {
            callback();
        }
    });
}