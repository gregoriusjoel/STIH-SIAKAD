/**
 * CKEditor 5 entry — di-load on-demand untuk admin pengumuman create.
 *
 * Catatan migrasi:
 * - `@ckeditor/ckeditor5-build-classic` (v41) sudah deprecated dan rentan XSS
 *   (GHSA-rgg8-g5x8-wr9v, fix di v43.2+). Pakai paket terpadu `ckeditor5` v44+.
 * - LicenseKey `'GPL'` wajib untuk self-hosted open-source usage.
 * - Plugins di v44+ harus disebut eksplisit; expose ke window agar blade existing
 *   bisa pakai `plugins: Object.values(CKEditorPlugins)`.
 */
import {
    ClassicEditor,
    Essentials,
    Heading,
    Paragraph,
    Bold,
    Italic,
    Underline,
    Strikethrough,
    Link,
    List,
    Indent,
    IndentBlock,
    BlockQuote,
    Table,
    TableToolbar,
    Undo,
    AutoLink,
} from 'ckeditor5';
import 'ckeditor5/ckeditor5.css';

window.ClassicEditor = ClassicEditor;
window.CKEditorPlugins = {
    Essentials,
    Heading,
    Paragraph,
    Bold,
    Italic,
    Underline,
    Strikethrough,
    Link,
    AutoLink,
    List,
    Indent,
    IndentBlock,
    BlockQuote,
    Table,
    TableToolbar,
    Undo,
};
