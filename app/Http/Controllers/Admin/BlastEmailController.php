<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BlastEmailRequest;
use App\Models\Mahasiswa;
use App\Models\Prodi;
use App\Models\KelasPerkuliahan;
use App\Services\BlastEmailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BlastEmailController extends Controller
{
    public function __construct(
        protected BlastEmailService $blastService
    ) {}

    /**
     * Show blast email form
     */
    public function index()
    {
        $prodis = Prodi::orderBy('nama_prodi')->get();
        $tingkatList = range(1, 4);
        $angkatanList = Mahasiswa::distinct()->orderBy('angkatan', 'desc')->pluck('angkatan')->toArray();

        return view('admin.blast-email.index', compact('prodis', 'tingkatList', 'angkatanList'));
    }

    /**
     * Get recipient preview berdasarkan filters
     */
    public function getPreview(Request $request)
    {
        $filters = $this->buildFilters($request);
        $preview = $this->blastService->getRecipientPreview($filters);

        return response()->json([
            'success' => true,
            'total_recipients' => $preview['total_recipients'],
            'sample' => $preview['sample']->map(fn($m) => [
                'nama' => $m->nama,
                'email' => $m->email_pribadi,
            ]),
        ]);
    }

    /**
     * Send blast email
     */
    public function send(BlastEmailRequest $request)
    {
        try {
            $filters = $this->buildFilters($request);
            $isCredentials = $request->boolean('send_credentials', false);

            if ($isCredentials) {
                // Send credentials (email kampus + password)
                $result = $this->blastService->sendCredentials(
                    filters: $filters,
                    senderId: auth()->id(),
                    immediate: $request->boolean('immediate', false),
                    scheduledAt: $request->input('scheduled_at'),
                    customSubject: $request->input('subject'),
                    customGreeting: $request->input('greeting'),
                    customMessage: $request->input('message')
                );

                Log::info('[CREDENTIALS BLAST] Sent successfully', [
                    'batch_id' => $result['batch_id'],
                    'recipients' => $result['total_recipients'],
                    'user_id' => auth()->id(),
                ]);
            } else {
                // Send regular blast email
                $result = $this->blastService->send(
                    subject: $request->subject,
                    greeting: $request->greeting,
                    message: $request->message,
                    filters: $filters,
                    senderId: auth()->id(),
                    immediate: $request->boolean('immediate', false),
                    scheduledAt: $request->input('scheduled_at')
                );

                Log::info('[BLAST EMAIL] Sent successfully', [
                    'batch_id' => $result['batch_id'],
                    'recipients' => $result['total_recipients'],
                    'user_id' => auth()->id(),
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => "Email berhasil dikirim ke {$result['total_recipients']} penerima",
                'batch_id' => $result['batch_id'],
            ]);
        } catch (\Throwable $e) {
            Log::error('[BLAST EMAIL] Failed', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengirim email: ' . $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Show blast email history/logs
     */
    public function logs(Request $request)
    {
        $query = \DB::table('email_blast_logs')
            ->leftJoin('mahasiswas', 'email_blast_logs.mahasiswa_id', '=', 'mahasiswas.id')
            ->leftJoin('users', 'mahasiswas.user_id', '=', 'users.id')
            ->when($request->batch_id, fn($q) => $q->where('batch_id', $request->batch_id))
            ->when($request->status, fn($q) => $q->where('success', $request->status === 'success'))
            ->select(
                'email_blast_logs.*',
                'users.name as nama',
                'mahasiswas.nim'
            )
            ->orderByDesc('email_blast_logs.created_at');

        $logs = $query->paginate(10);

        // Convert timestamp strings to Carbon instances in items only
        $logs->setCollection($logs->getCollection()->map(function($item) {
            $item->created_at = \Carbon\Carbon::parse($item->created_at);
            $item->updated_at = \Carbon\Carbon::parse($item->updated_at);
            return $item;
        }));

        // Return JSON untuk AJAX request
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $logs->items(),
                'total' => $logs->total(),
                'per_page' => $logs->perPage(),
                'current_page' => $logs->currentPage(),
            ]);
        }

        // Return view untuk page load
        return view('admin.blast-email.logs', compact('logs'));
    }

    /**
     * Get blast statistics
     */
    public function stats(Request $request)
    {
        $batchId = $request->batch_id;
        $stats = $this->blastService->getBlastStats($batchId);

        return response()->json([
            'success' => true,
            'data' => $stats,
        ]);
    }

    /**
     * Build filters dari request
     */
    protected function buildFilters(Request $request): array
    {
        $filters = [];

        if ($request->filled('filter_type')) {
            $filterType = $request->filter_type;

            if ($filterType === 'all') {
                // Semua mahasiswa
            } elseif ($filterType === 'angkatan') {
                if ($request->filled('angkatan')) {
                    $filters['angkatan'] = $request->angkatan;
                }
            } elseif ($filterType === 'prodi') {
                if ($request->filled('prodi_id')) {
                    $filters['prodi_id'] = $request->prodi_id;
                }
            } elseif ($filterType === 'tingkat') {
                if ($request->filled('tingkat')) {
                    $filters['tingkat'] = $request->tingkat;
                }
            } elseif ($filterType === 'kelas') {
                if ($request->filled('kelas_perkuliahan_id')) {
                    $filters['kelas_perkuliahan_id'] = $request->kelas_perkuliahan_id;
                }
            } elseif ($filterType === 'status') {
                if ($request->filled('status')) {
                    $filters['status'] = $request->status;
                }
            } elseif ($filterType === 'spesifik') {
                if ($request->filled('mahasiswa_ids')) {
                    $filters['mahasiswa_ids'] = $request->mahasiswa_ids;
                }
            }
        }

        // Optional: hanya mahasiswa dengan email terverifikasi
        if ($request->boolean('verified_only')) {
            $filters['verified_only'] = true;
        }

        return $filters;
    }

    /**
     * Get kelas untuk selected prodi (AJAX)
     */
    public function getKelasPerProdi($prodiId)
    {
        $kelas = KelasPerkuliahan::where('prodi_id', $prodiId)
            ->orderBy('tingkat')
            ->orderBy('kode_kelas')
            ->get(['id', 'nama_kelas', 'tingkat']);

        return response()->json($kelas);
    }

    /**
     * Search mahasiswa untuk TomSelect filter spesifik (AJAX)
     */
    public function searchMahasiswa(Request $request)
    {
        $query = $request->input('q');

        $mahasiswaQuery = \App\Models\Mahasiswa::where('status', 'aktif')
            ->with('user:id,name');

        if (!empty($query)) {
            $mahasiswaQuery->where(function($q) use ($query) {
                $q->where('nim', 'like', "%{$query}%")
                  ->orWhereHas('user', function($userQuery) use ($query) {
                      $userQuery->where('name', 'like', "%{$query}%");
                  });
            });
        }

        $mahasiswa = $mahasiswaQuery->limit(50)->get(['id', 'nim', 'user_id']);

        $formatted = $mahasiswa->map(function($m) {
            return [
                'id' => $m->id,
                'nim' => $m->nim,
                'nama' => $m->user ? $m->user->name : 'Tanpa Nama'
            ];
        });

        return response()->json($formatted);
    }

    /**
     * Tampilkan halaman Outbox (Email Menunggu/Terjadwal)
     */
    public function outbox(Request $request)
    {
        $outboxes = \App\Models\EmailOutbox::with('mahasiswa.user')
            ->orderByRaw("FIELD(status, 'pending', 'failed', 'sent', 'cancelled')")
            ->orderBy('scheduled_at', 'asc')
            ->paginate(10);

        return view('admin.blast-email.outbox', compact('outboxes'));
    }

    /**
     * Update isi pesan email di outbox
     */
    public function updateOutbox(Request $request, \App\Models\EmailOutbox $outbox)
    {
        $request->validate([
            'subject' => 'nullable|string|max:200',
            'greeting' => 'nullable|string|max:100',
            'message_body' => 'nullable|string|max:5000',
        ]);

        if ($outbox->status !== 'pending' && $outbox->status !== 'failed') {
            return back()->with('error', 'Hanya email yang masih pending atau gagal yang dapat diedit.');
        }

        $outbox->update([
            'subject' => $request->subject,
            'greeting' => $request->greeting,
            'message_body' => $request->message_body,
        ]);

        return back()->with('success', 'Pesan email berhasil diperbarui.');
    }

    /**
     * Batalkan/hapus email dari outbox
     */
    public function destroyOutbox(\App\Models\EmailOutbox $outbox)
    {
        if ($outbox->status === 'sent') {
            return back()->with('error', 'Email yang sudah terkirim tidak dapat dibatalkan.');
        }

        $outbox->delete();

        return back()->with('success', 'Email berhasil dibatalkan dari antrean.');
    }
}
