/**
 * pdfjs-dist entry — di-load on-demand di halaman yang parse PDF (kalender-akademik).
 * Vite-friendly worker import: pdf.worker.min.mjs di-bundle ke public/build/assets/
 * dan workerSrc otomatis di-resolve.
 */
import * as pdfjsLib from 'pdfjs-dist';
import pdfjsWorker from 'pdfjs-dist/build/pdf.worker.min.mjs?url';

pdfjsLib.GlobalWorkerOptions.workerSrc = pdfjsWorker;

// Expose globally so legacy inline handlers can keep using `pdfjsLib.getDocument(...)`.
window.pdfjsLib = pdfjsLib;
