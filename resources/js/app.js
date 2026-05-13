import './bootstrap';

/* ──────────────────────────────────────────────────────────────────────
 * Bundle previously-CDN globals (Bagian 8 — kurangi cross-domain script
 * dependency & hilangkan kebutuhan SRI per-URL). Expose ke window agar
 * inline blade scripts yang existing tetap jalan tanpa diubah.
 * ────────────────────────────────────────────────────────────────────── */

// jQuery — dipakai sebagai legacy global di banyak halaman.
import $ from 'jquery';
window.$ = window.jQuery = $;

// Alpine.js + collapse plugin (sebelumnya dimuat via cdn.min.js + ?alpinejs/collapse).
import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';
Alpine.plugin(collapse);
window.Alpine = Alpine;
Alpine.start();

// SweetAlert2 — global tersedia sebagai `Swal`.
import Swal from 'sweetalert2';
window.Swal = Swal;

// ApexCharts — dipakai di banyak dashboard.
import ApexCharts from 'apexcharts';
window.ApexCharts = ApexCharts;

// Flatpickr + locale Indonesia.
import flatpickr from 'flatpickr';
import { Indonesian } from 'flatpickr/dist/l10n/id.js';
flatpickr.localize(Indonesian);
window.flatpickr = flatpickr;

import './sweetalert-helper';
