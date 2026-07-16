<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class DevDocsController extends Controller
{
    private string $docsPath;

    public function __construct()
    {
        $this->docsPath = base_path('database/dev-docs');
        
        // Pastikan folder database/dev-docs ada
        if (!File::exists($this->docsPath)) {
            File::makeDirectory($this->docsPath, 0755, true);
        }
    }

    /**
     * Tampilkan halaman dokumentasi
     */
    public function index(Request $request)
    {
        // Tolak akses jika di production
        if (!app()->environment('local')) {
            abort(404);
        }

        // Ambil semua file .md di folder dev-docs
        $files = File::files($this->docsPath);
        $documents = [];
        
        foreach ($files as $file) {
            if ($file->getExtension() === 'md' && $file->getFilename() !== 'todo.md') {
                $documents[] = $file->getFilename();
            }
        }

        // Jika tidak ada dokumen, buat changelog.md default
        if (empty($documents)) {
            $defaultFile = 'changelog.md';
            File::put($this->docsPath . '/' . $defaultFile, "# Dokumentasi Proyek\n\nSelamat datang di dokumentasi pengembang.");
            $documents[] = $defaultFile;
        }

        sort($documents);

        // Tentukan dokumen aktif (hanya jika diminta secara eksplisit)
        $activeDoc = null;
        $content = null;

        if ($request->has('doc')) {
            $activeDoc = $request->get('doc');
            
            // Hindari directory traversal attack
            $activeDoc = basename($activeDoc);
            if ($activeDoc !== 'board.json') {
                if (!str_ends_with($activeDoc, '.md')) {
                    $activeDoc .= '.md';
                }
            }

            $filePath = $this->docsPath . '/' . $activeDoc;

            // Inisialisasi board.json jika belum ada
            if ($activeDoc === 'board.json' && !File::exists($filePath)) {
                $defaultBoard = [
                    'columns' => [
                        [
                            'id' => 'backlog',
                            'title' => 'Revisi Baru (Backlog)',
                            'cards' => [
                                [
                                    'id' => 'card-1',
                                    'title' => 'Mengoptimasi database perkuliahan',
                                    'desc' => 'Lakukan perapihan indeks unik dan hapus indeks yang redundan.',
                                    'date' => date('Y-m-d')
                                ],
                                [
                                    'id' => 'card-2',
                                    'title' => 'Menyatukan modul pembayaran',
                                    'desc' => 'Drop tabel pembayaran legacy dan pindahkan dashboard untuk memuat data dari tabel invoices.',
                                    'date' => date('Y-m-d')
                                ]
                            ]
                        ],
                        [
                            'id' => 'progress',
                            'title' => 'Sedang Dikerjakan',
                            'cards' => []
                        ],
                        [
                            'id' => 'testing',
                            'title' => 'Uji Coba (Testing)',
                            'cards' => []
                        ],
                        [
                            'id' => 'done',
                            'title' => 'Selesai (Done)',
                            'cards' => []
                        ]
                    ]
                ];
                File::put($filePath, json_encode($defaultBoard, JSON_PRETTY_PRINT));
            }

            if (File::exists($filePath)) {
                $content = File::get($filePath);
            } else {
                $activeDoc = null;
            }
        }

        return view('dev-docs.index', compact('documents', 'activeDoc', 'content'));
    }

    /**
     * Simpan isi dokumen
     */
    public function save(Request $request)
    {
        if (!app()->environment('local')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'doc' => 'required|string',
            'content' => 'required|string',
        ]);

        $doc = basename($request->input('doc'));
        if ($doc !== 'board.json') {
            if (!str_ends_with($doc, '.md')) {
                $doc .= '.md';
            }
        }

        $filePath = $this->docsPath . '/' . $doc;

        File::put($filePath, $request->input('content'));

        return response()->json([
            'success' => true,
            'message' => 'Dokumen berhasil disimpan!',
            'doc' => $doc
        ]);
    }

    /**
     * Buat dokumen baru
     */
    public function create(Request $request)
    {
        if (!app()->environment('local')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'name' => 'required|string|max:50',
        ]);

        $name = preg_replace('/[^a-zA-Z0-9_\-]/', '', $request->input('name'));
        $filename = strtolower($name) . '.md';
        
        $filePath = $this->docsPath . '/' . $filename;

        if (File::exists($filePath)) {
            return response()->json([
                'success' => false,
                'message' => 'Dokumen dengan nama tersebut sudah ada!'
            ], 422);
        }

        // Tulis template default
        $title = ucwords(str_replace(['-', '_'], ' ', $name));
        File::put($filePath, "# {$title}\n\nTulis dokumentasi Anda di sini.");

        return response()->json([
            'success' => true,
            'message' => 'Dokumen berhasil dibuat!',
            'doc' => $filename
        ]);
    }

    /**
     * Hapus dokumen
     */
    public function delete(Request $request)
    {
        if (!app()->environment('local')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'doc' => 'required|string',
        ]);

        $doc = basename($request->input('doc'));
        
        // Proteksi dokumen bawaan sistem agar tidak bisa dihapus
        if ($doc === 'changelog.md' || $doc === 'panduan.md') {
            return response()->json([
                'success' => false,
                'message' => 'Dokumen bawaan sistem tidak boleh dihapus!'
            ], 403);
        }

        $filePath = $this->docsPath . '/' . $doc;

        if (File::exists($filePath)) {
            File::delete($filePath);
            return response()->json([
                'success' => true,
                'message' => 'Dokumen berhasil dihapus!'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Dokumen tidak ditemukan!'
        ], 444);
    }
}
