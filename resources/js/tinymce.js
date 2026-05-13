/**
 * TinyMCE v6 entry — di-load on-demand untuk halaman dosen kelas (lihat-rincian).
 * Vite akan bundle skin/icons/theme/plugins yang dipakai. Pastikan `tinymce.init()`
 * di blade TIDAK menyertakan `base_url`/`suffix` agar resource resolution mengikuti
 * Vite (relative ke modul yang di-import).
 */
import tinymce from 'tinymce/tinymce';

// Default theme, model, icons
import 'tinymce/icons/default';
import 'tinymce/themes/silver';
import 'tinymce/models/dom';

// Skins (CSS)
import 'tinymce/skins/ui/oxide/skin.min.css';
import 'tinymce/skins/ui/oxide/content.min.css';
import 'tinymce/skins/content/default/content.min.css';

// Plugins yang digunakan (sesuai tinymce.init plugins: 'lists link')
import 'tinymce/plugins/lists';
import 'tinymce/plugins/link';

window.tinymce = tinymce;
