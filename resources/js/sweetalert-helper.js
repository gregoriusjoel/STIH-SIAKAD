/**
 * SweetAlert2 Global Helper Functions
 * 
 * Centralized notification system for consistent UI/UX across the application.
 * All alerts, confirms, and notifications should use these helpers.
 * 
 * Usage:
 *   - showSuccess('Data berhasil disimpan')
 *   - showError('Gagal menyimpan data')
 *   - showConfirm('Hapus data ini?', callback)
 *   - showWarning('Perhatian!', 'Aksi tidak dapat dibatalkan')
 *   - showInfo('Informasi', 'Fitur dalam pengembangan')
 * 
 * @requires SweetAlert2
 */

/**
 * Default SweetAlert2 configuration
 */
const defaultConfig = {
    confirmButtonColor: '#8B1538',
    cancelButtonColor: '#6B7280',
    confirmButtonText: 'OK',
    cancelButtonText: 'Batal',
    reverseButtons: true,
    customClass: {
        confirmButton: 'px-6 py-2.5 rounded-lg font-semibold shadow-lg',
        cancelButton: 'px-6 py-2.5 rounded-lg font-semibold',
        popup: 'rounded-2xl',
        title: 'text-xl font-bold',
        htmlContainer: 'text-gray-600'
    }
};

/**
 * Internal: decode simple escaped characters from inline handler message.
 *
 * @param {string} value
 * @returns {string}
 */
function decodeInlineDialogMessage(value) {
    return String(value || '')
        .replace(/\\n/g, '\n')
        .replace(/\\r/g, '\r')
        .replace(/\\t/g, '\t')
        .replace(/\\"/g, '"')
        .replace(/\\'/g, "'");
}

/**
 * Internal: extract confirm message from inline handler code.
 * Supports formats like:
 * - return confirm('text')
 * - confirm("text")
 * - return confirm(`text`)
 *
 * @param {string} handlerCode
 * @returns {string|null}
 */
function extractInlineConfirmMessage(handlerCode) {
    if (!handlerCode) return null;

    const match = String(handlerCode).match(/confirm\s*\(\s*(["'`])([\s\S]*?)\1\s*\)/i);
    if (!match || typeof match[2] !== 'string') return null;

    return decodeInlineDialogMessage(match[2]).trim();
}

/**
 * Internal: submit form once, bypassing SweetAlert interception to prevent loops.
 *
 * @param {HTMLFormElement} form
 * @param {HTMLElement|null} submitter
 */
function submitFormWithSwalBypass(form, submitter = null) {
    if (!form) return;

    form.dataset.swalBypassSubmit = '1';
    // Mark that form was submitted from confirmation dialog, so don't show success message
    form.dataset.skipSuccessMessage = '1';
    
    // Add hidden input to form to signal to backend that this was submitted from confirmation
    // This will prevent backend from sending success message
    if (!form.querySelector('input[name="_from_confirmation"]')) {
        const hiddenInput = document.createElement('input');
        hiddenInput.type = 'hidden';
        hiddenInput.name = '_from_confirmation';
        hiddenInput.value = '1';
        form.appendChild(hiddenInput);
    }
    
    // Hide any existing success alerts before submitting
    // Success alert has role="alert" and bg-gradient-to-r class with green colors
    const successAlert = document.querySelector('div[role="alert"].bg-gradient-to-r.from-green-50');
    if (successAlert && successAlert.closest('.mb-4')) {
        successAlert.closest('.mb-4').style.display = 'none';
    }

    if (typeof form.requestSubmit === 'function') {
        if (submitter) {
            try {
                form.requestSubmit(submitter);
                return;
            } catch (_err) {
                // Fallback to generic submit below.
            }
        }
        form.requestSubmit();
        return;
    }

    form.submit();
}

/**
 * Show success notification
 * 
 * @param {string} message - Success message
 * @param {string} title - Optional title (default: 'Berhasil!')
 * @param {function} callback - Optional callback after close
 */
window.showSuccess = function(message, title = 'Berhasil!', callback = null) {
    Swal.fire({
        icon: 'success',
        title: title,
        text: message,
        timer: 3000,
        timerProgressBar: true,
        showConfirmButton: true,
        ...defaultConfig
    }).then((result) => {
        if (callback && typeof callback === 'function') {
            callback(result);
        }
    });
};

/**
 * Show error notification
 * 
 * @param {string} message - Error message
 * @param {string} title - Optional title (default: 'Gagal!')
 * @param {function} callback - Optional callback after close
 */
window.showError = function(message, title = 'Gagal!', callback = null) {
    Swal.fire({
        icon: 'error',
        title: title,
        text: message,
        showConfirmButton: true,
        ...defaultConfig
    }).then((result) => {
        if (callback && typeof callback === 'function') {
            callback(result);
        }
    });
};

/**
 * Show warning notification
 * 
 * @param {string} title - Warning title
 * @param {string} message - Warning message
 * @param {function} callback - Optional callback after close
 */
window.showWarning = function(title, message, callback = null) {
    Swal.fire({
        icon: 'warning',
        title: title,
        text: message,
        showConfirmButton: true,
        ...defaultConfig
    }).then((result) => {
        if (callback && typeof callback === 'function') {
            callback(result);
        }
    });
};

/**
 * Show info notification
 * 
 * @param {string} title - Info title
 * @param {string} message - Info message
 * @param {function} callback - Optional callback after close
 */
window.showInfo = function(title, message, callback = null) {
    Swal.fire({
        icon: 'info',
        title: title,
        text: message,
        showConfirmButton: true,
        ...defaultConfig
    }).then((result) => {
        if (callback && typeof callback === 'function') {
            callback(result);
        }
    });
};

/**
 * Show confirmation dialog
 * 
 * @param {string} message - Confirmation message
 * @param {function} onConfirm - Callback when confirmed
 * @param {function} onCancel - Optional callback when cancelled
 * @param {string} title - Optional title (default: 'Konfirmasi')
 * @param {string} confirmText - Optional confirm button text
 * @param {string} cancelText - Optional cancel button text
 */
window.showConfirm = function(
    message, 
    onConfirm, 
    onCancel = null, 
    title = 'Konfirmasi', 
    confirmText = 'Ya, Lanjutkan',
    cancelText = 'Batal'
) {
    Swal.fire({
        icon: 'question',
        title: title,
        text: message,
        showCancelButton: true,
        confirmButtonText: confirmText,
        cancelButtonText: cancelText,
        ...defaultConfig
    }).then((result) => {
        if (result.isConfirmed) {
            if (onConfirm && typeof onConfirm === 'function') {
                onConfirm();
            }
        } else if (result.isDismissed) {
            if (onCancel && typeof onCancel === 'function') {
                onCancel();
            }
        }
    });
};

/**
 * Promise-based confirmation helper.
 *
 * @param {string} message
 * @param {object} options
 * @returns {Promise<boolean>}
 */
window.swalConfirmAsync = function(message, options = {}) {
    return Swal.fire({
        icon: options.icon || 'question',
        title: options.title || 'Konfirmasi',
        text: message,
        showCancelButton: true,
        confirmButtonText: options.confirmButtonText || 'Ya, Lanjutkan',
        cancelButtonText: options.cancelButtonText || 'Batal',
        confirmButtonColor: options.confirmButtonColor || defaultConfig.confirmButtonColor,
        cancelButtonColor: options.cancelButtonColor || defaultConfig.cancelButtonColor,
        reverseButtons: defaultConfig.reverseButtons,
        customClass: defaultConfig.customClass
    }).then(result => result.isConfirmed);
};

/**
 * Promise-based prompt helper.
 *
 * @param {string} title
 * @param {object} options
 * @returns {Promise<{isConfirmed: boolean, value: string}>}
 */
window.swalPromptAsync = function(title, options = {}) {
    return Swal.fire({
        title: title || 'Masukkan Nilai',
        input: options.input || 'text',
        inputPlaceholder: options.inputPlaceholder || '',
        inputValue: options.inputValue || '',
        inputAttributes: options.inputAttributes || {},
        showCancelButton: true,
        confirmButtonText: options.confirmButtonText || 'Simpan',
        cancelButtonText: options.cancelButtonText || 'Batal',
        confirmButtonColor: options.confirmButtonColor || defaultConfig.confirmButtonColor,
        cancelButtonColor: options.cancelButtonColor || defaultConfig.cancelButtonColor,
        reverseButtons: defaultConfig.reverseButtons,
        customClass: defaultConfig.customClass
    }).then(result => ({
        isConfirmed: result.isConfirmed,
        value: result.value
    }));
};

/**
 * Show delete confirmation (specialized version of confirm)
 * 
 * @param {string} itemName - Name of item to delete
 * @param {function} onConfirm - Callback when confirmed
 * @param {function} onCancel - Optional callback when cancelled
 */
window.showDeleteConfirm = function(itemName, onConfirm, onCancel = null) {
    Swal.fire({
        icon: 'warning',
        title: 'Hapus Data?',
        html: `Apakah Anda yakin ingin menghapus <strong>${itemName}</strong>?<br><small class="text-gray-500">Tindakan ini tidak dapat dibatalkan.</small>`,
        showCancelButton: true,
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#DC2626',
        ...defaultConfig
    }).then((result) => {
        if (result.isConfirmed) {
            if (onConfirm && typeof onConfirm === 'function') {
                onConfirm();
            }
        } else if (result.isDismissed) {
            if (onCancel && typeof onCancel === 'function') {
                onCancel();
            }
        }
    });
};

/**
 * Show loading alert
 * 
 * @param {string} message - Loading message
 */
window.showLoading = function(message = 'Memproses...') {
    Swal.fire({
        title: message,
        allowOutsideClick: false,
        allowEscapeKey: false,
        showConfirmButton: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
};

/**
 * Close loading alert
 */
window.closeLoading = function() {
    Swal.close();
};

/**
 * Show toast notification (non-blocking)
 * 
 * @param {string} message - Toast message
 * @param {string} icon - Icon type: 'success', 'error', 'warning', 'info'
 * @param {number} timer - Auto-close timer in ms (default: 3000)
 */
window.showToast = function(message, icon = 'success', timer = 3000) {
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: timer,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer);
            toast.addEventListener('mouseleave', Swal.resumeTimer);
        },
        customClass: {
            popup: 'rounded-xl shadow-xl'
        }
    });

    Toast.fire({
        icon: icon,
        title: message
    });
};

/**
 * Show validation error with list of errors
 * 
 * @param {object|array} errors - Validation errors (Laravel format or array)
 * @param {string} title - Optional title
 */
window.showValidationErrors = function(errors, title = 'Validasi Gagal') {
    let errorList = '';
    
    if (Array.isArray(errors)) {
        errorList = '<ul class="text-left list-disc list-inside">' + 
            errors.map(err => `<li class="text-sm">${err}</li>`).join('') + 
            '</ul>';
    } else if (typeof errors === 'object') {
        const errorArray = Object.values(errors).flat();
        errorList = '<ul class="text-left list-disc list-inside">' + 
            errorArray.map(err => `<li class="text-sm">${err}</li>`).join('') + 
            '</ul>';
    } else {
        errorList = `<p class="text-sm">${errors}</p>`;
    }

    Swal.fire({
        icon: 'error',
        title: title,
        html: errorList,
        showConfirmButton: true,
        ...defaultConfig
    });
};

/**
 * Show AJAX error from response
 * 
 * @param {object} error - Axios/AJAX error object
 * @param {string} defaultMessage - Default message if error parsing fails
 */
window.showAjaxError = function(error, defaultMessage = 'Terjadi kesalahan pada server') {
    let message = defaultMessage;
    
    if (error.response) {
        // Server responded with error status
        if (error.response.data) {
            if (error.response.data.message) {
                message = error.response.data.message;
            } else if (error.response.data.error) {
                message = error.response.data.error;
            } else if (error.response.data.errors) {
                // Validation errors
                showValidationErrors(error.response.data.errors);
                return;
            }
        }
        
        // Add status code info
        if (error.response.status) {
            message += ` (${error.response.status})`;
        }
    } else if (error.request) {
        // Request made but no response
        message = 'Tidak dapat terhubung ke server. Periksa koneksi internet Anda.';
    } else if (error.message) {
        // Other errors
        message = error.message;
    }
    
    showError(message);
};

/**
 * Handle Laravel session flash messages
 * Automatically shows SweetAlert for session messages
 */
document.addEventListener('DOMContentLoaded', function() {
    // Check for flash messages in page meta or data attributes
    const successMsg = document.querySelector('[data-flash-success]');
    const errorMsg = document.querySelector('[data-flash-error]');
    const warningMsg = document.querySelector('[data-flash-warning]');
    const infoMsg = document.querySelector('[data-flash-info]');
    
    // Check if form submitted from confirmation dialog (skip success message in that case)
    const skipSuccessMsg = document.querySelector('form[data-skip-success-message="1"]') !== null;
    
    if (successMsg && !skipSuccessMsg) {
        showSuccess(successMsg.getAttribute('data-flash-success'));
        successMsg.remove();
    }
    
    if (errorMsg) {
        showError(errorMsg.getAttribute('data-flash-error'));
        errorMsg.remove();
    }
    
    if (warningMsg) {
        showWarning('Perhatian', warningMsg.getAttribute('data-flash-warning'));
        warningMsg.remove();
    }
    
    if (infoMsg) {
        showInfo('Informasi', infoMsg.getAttribute('data-flash-info'));
        infoMsg.remove();
    }

    // Auto-upgrade inline native confirm dialogs (onsubmit/onclick) to SweetAlert.
    document.querySelectorAll('form[onsubmit*="confirm("]').forEach(form => {
        const inlineHandler = form.getAttribute('onsubmit') || '';
        const message = extractInlineConfirmMessage(inlineHandler);
        if (!message) return;

        form.removeAttribute('onsubmit');
        form.addEventListener('submit', function(e) {
            if (form.dataset.swalBypassSubmit === '1') {
                form.dataset.swalBypassSubmit = '0';
                return;
            }

            e.preventDefault();
            swalConfirmAsync(message).then(isConfirmed => {
                if (isConfirmed) {
                    submitFormWithSwalBypass(form);
                }
            });
        });
    });

    document.querySelectorAll('[onclick*="confirm("]').forEach(el => {
        const inlineHandler = el.getAttribute('onclick') || '';
        const message = extractInlineConfirmMessage(inlineHandler);
        if (!message) return;

        el.removeAttribute('onclick');
        el.addEventListener('click', function(e) {
            if (el.dataset.swalBypassClick === '1') {
                el.dataset.swalBypassClick = '0';
                return;
            }

            e.preventDefault();
            e.stopPropagation();

            swalConfirmAsync(message).then(isConfirmed => {
                if (!isConfirmed) return;

                const form = el.form || el.closest('form');
                if (form) {
                    submitFormWithSwalBypass(form, el);
                    return;
                }

                const href = el.getAttribute('href');
                if (href && href !== '#') {
                    window.location.assign(href);
                }
            });
        });
    });

    // Global replacement for native alert to keep UI consistent with SweetAlert.
    if (window.Swal && typeof window.alert === 'function' && !window.__nativeAlertPatched) {
        window.__nativeAlertPatched = true;
        const originalAlert = window.alert.bind(window);
        window.__originalAlert = originalAlert;

        window.alert = function(message) {
            if (!window.Swal) {
                return originalAlert(message);
            }

            Swal.fire({
                icon: 'warning',
                title: 'Informasi',
                text: String(message || ''),
                showConfirmButton: true,
                confirmButtonColor: defaultConfig.confirmButtonColor,
                customClass: defaultConfig.customClass
            });
        };
    }
});

/**
 * Global AJAX error handler integration
 * Automatically shows SweetAlert for AJAX errors
 */
if (window.axios) {
    // Already configured in bootstrap.js, but ensure errors are caught
    window.axios.interceptors.response.use(
        response => response,
        error => {
            // Only show alert if not in silent mode
            if (!error.config || !error.config.silent) {
                // Don't auto-show for validation errors (422)
                if (error.response && error.response.status === 422) {
                    if (error.response.data && error.response.data.errors) {
                        showValidationErrors(error.response.data.errors);
                    }
                } 
                // Show generic error for other status codes
                else if (error.response && error.response.status >= 500) {
                    showError('Terjadi kesalahan pada server. Silakan coba lagi.');
                }
            }
            return Promise.reject(error);
        }
    );
}

// Export for ES6 modules if needed
if (typeof module !== 'undefined' && module.exports) {
    module.exports = {
        showSuccess,
        showError,
        showWarning,
        showInfo,
        showConfirm,
        showDeleteConfirm,
        showLoading,
        closeLoading,
        showToast,
        showValidationErrors,
        showAjaxError
    };
}
