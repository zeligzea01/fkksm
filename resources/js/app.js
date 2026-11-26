import './bootstrap';
import Swal from 'sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';

window.Swal = Swal;

const fkksmSwalDefaults = {
    confirmButtonColor: '#10A9BC',
    cancelButtonColor: '#61758A',
    background: '#FFFFFF',
    color: '#132B45',
    customClass: {
        popup: 'rounded-xl border border-[#DCE6EE] shadow-xl font-sans',
        title: 'text-base font-bold text-[#132B45]',
        htmlContainer: 'text-xs text-[#61758A] leading-relaxed',
        confirmButton: 'rounded-lg px-5 py-2.5 text-xs font-semibold tracking-wide',
        cancelButton: 'rounded-lg px-5 py-2.5 text-xs font-semibold tracking-wide',
    },
};

/**
 * Display a unified SweetAlert2 modal notification for all FKKSM alerts.
 */
window.fkksmModal = function ({
    icon = 'info',
    title = 'Pemberitahuan',
    text = '',
    html = '',
    confirmButtonText = 'Tutup',
    showCancelButton = false,
    cancelButtonText = 'Batal',
}) {
    const config = {
        ...fkksmSwalDefaults,
        icon,
        title,
        confirmButtonText,
        showCancelButton,
        cancelButtonText,
    };
    if (html) {
        config.html = html;
    } else {
        config.text = text;
    }
    return Swal.fire(config);
};

function escapeHtml(str) {
    return String(str ?? '')
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#39;');
}

window.fkksmShowNotificationDetail = function (title, body, timestamp) {
    return window.fkksmModal({
        icon: 'info',
        title: title || 'Notifikasi Aktivitas',
        html: `<p class="text-slate-700 leading-relaxed">${escapeHtml(body)}</p><p class="mt-3 font-mono text-[11px] text-slate-400">${escapeHtml(timestamp)}</p>`,
        confirmButtonText: 'Tutup',
    });
};

document.addEventListener('DOMContentLoaded', () => {
    // 1. Trigger SweetAlert2 modal for any server-side Laravel flash / validation message
    const flash = window.__FKKSM_FLASH__;
    if (flash) {
        if (flash.errors && flash.errors.length > 0) {
            const listHtml = flash.errors
                .map((err) => `<li class="text-left">${escapeHtml(err)}</li>`)
                .join('');
            window.fkksmModal({
                icon: 'error',
                title: 'Periksa Kembali Data Anda',
                html: `<ul class="list-disc pl-5 space-y-1 text-xs text-rose-700">${listHtml}</ul>`,
                confirmButtonText: 'Perbaiki',
            });
        } else if (flash.error) {
            window.fkksmModal({
                icon: 'error',
                title: 'Terjadi Kesalahan',
                text: flash.error,
                confirmButtonText: 'Mengerti',
            });
        } else if (flash.warning) {
            window.fkksmModal({
                icon: 'warning',
                title: 'Perhatian',
                text: flash.warning,
                confirmButtonText: 'Baik',
            });
        } else if (flash.info) {
            window.fkksmModal({
                icon: 'info',
                title: 'Informasi',
                text: flash.info,
                confirmButtonText: 'OK',
            });
        } else if (flash.success) {
            window.fkksmModal({
                icon: 'success',
                title: 'Berhasil',
                text: flash.success,
                confirmButtonText: 'OK',
            });
        }
    }

    // 2. Intercept all form[data-confirm] submissions with SweetAlert2 confirmation modal
    document.addEventListener('submit', (e) => {
        const form = e.target;
        if (!(form instanceof HTMLFormElement)) return;
        const msg = form.getAttribute('data-confirm');
        if (!msg || form.dataset.swalConfirmed === '1') return;

        e.preventDefault();
        const title = form.getAttribute('data-confirm-title') || 'Konfirmasi Tindakan';
        const btnText = form.getAttribute('data-confirm-btn') || 'Ya, Lanjutkan';

        Swal.fire({
            ...fkksmSwalDefaults,
            icon: 'warning',
            title,
            text: msg,
            showCancelButton: true,
            confirmButtonText: btnText,
            cancelButtonText: 'Batal',
        }).then((result) => {
            if (result.isConfirmed) {
                form.dataset.swalConfirmed = '1';
                form.submit();
            }
        });
    });
});
