/**
 * FullCalendar entry — di-load on-demand di halaman kalender-akademik.
 * Pakai paket aggregator `fullcalendar` v6 (sudah include daygrid/timegrid/list/interaction).
 */
import { Calendar } from 'fullcalendar';

// Expose global agar inline blade scripts yang existing (yang akses `FullCalendar`/`Calendar`)
// tetap jalan tanpa refactor besar.
window.FullCalendar = { Calendar };
