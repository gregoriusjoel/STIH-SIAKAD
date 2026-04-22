<?php

namespace App\Services;

use App\Jobs\SendBlastEmailJob;
use App\Models\Mahasiswa;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BlastEmailService
{
    /**
     * Rate limiting configuration
     */
    protected const MAX_BLAST_PER_HOUR = 10;
    protected const RATE_LIMIT_KEY_PREFIX = 'blast_email_';

    /**
     * Kirim blast email ke mahasiswa dengan berbagai filter
     * 
     * @param string $subject Subject email
     * @param string $greeting Greeting/Salutation
     * @param string $message Isi pesan
     * @param array $filters Filter: prodi_id, kelas_perkuliahan_id, tingkat, program_studi, etc
     * @param int|null $senderId User ID yang mengirim
     * @param bool $immediate Kirim langsung atau queue?
     * @return array{success: bool, batch_id: string, total_recipients: int, queued: int}
     */
    public function send(
        string $subject,
        string $greeting,
        string $message,
        array $filters = [],
        ?int $senderId = null,
        bool $immediate = false,
        ?string $scheduledAt = null
    ): array {
        // Check rate limit
        if (!$this->checkRateLimit($senderId)) {
            throw new \RuntimeException('Blast email rate limit exceeded. Coba lagi nanti.');
        }

        // Build query berdasarkan filters
        $query = $this->buildQuery($filters);
        $mahasiswaIds = $query->pluck('mahasiswas.id')->toArray();

        if (empty($mahasiswaIds)) {
            return [
                'success' => true,
                'batch_id' => uniqid('blast_'),
                'total_recipients' => 0,
                'queued' => 0,
            ];
        }

        $batchId = uniqid('blast_');

        // Log blast request
        Log::info("[BLAST EMAIL] Request baru", [
            'batch_id' => $batchId,
            'subject' => $subject,
            'recipients' => count($mahasiswaIds),
            'filters' => $filters,
            'sender_id' => $senderId,
            'immediate' => $immediate,
        ]);

        // Kirim langsung atau masukkan ke Outbox
        if ($immediate) {
            $job = new SendBlastEmailJob(
                $mahasiswaIds,
                $subject,
                $greeting,
                $message,
                actionUrl: 'https://satu.axiona.id',
                actionText: 'Login ke SIAKAD',
                senderId: $senderId
            );
            $job->setBatchId($batchId); // Set manually using the setter
            dispatch_sync($job);
            $queued = count($mahasiswaIds);
        } else {
            $outboxData = [];
            $now = now();
            $schedule = $scheduledAt ? \Carbon\Carbon::parse($scheduledAt) : $now;
            
            // Get valid emails
            $mahasiswas = Mahasiswa::whereIn('id', $mahasiswaIds)->get(['id', 'email_pribadi', 'email_kampus']);
            
            foreach ($mahasiswas as $m) {
                $target = $m->email_pribadi ?: $m->email_kampus;
                if (!$target) continue;

                $outboxData[] = [
                    'batch_id' => $batchId,
                    'mahasiswa_id' => $m->id,
                    'target_email' => $target,
                    'subject' => $subject,
                    'greeting' => $greeting,
                    'message_body' => $message,
                    'is_credentials_mode' => false,
                    'status' => 'pending',
                    'scheduled_at' => $schedule,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
            
            foreach (array_chunk($outboxData, 500) as $chunk) {
                \App\Models\EmailOutbox::insert($chunk);
            }
            $queued = count($outboxData);
        }

        // Update rate limit
        $this->incrementRateLimit($senderId);

        return [
            'success' => true,
            'batch_id' => $batchId,
            'total_recipients' => count($mahasiswaIds),
            'queued' => $queued,
        ];
    }

    /**
     * Build query untuk select mahasiswa berdasarkan filters
     * 
     * @param array $filters
     * @return Builder
     */
    protected function buildQuery(array $filters): Builder
    {
        $query = Mahasiswa::select('mahasiswas.id', 'mahasiswas.email_pribadi')
            ->where('mahasiswas.user_id', '!=', null); // Hanya mahasiswa yang sudah punya akun

        // Filter berdasarkan prodi_id
        if (!empty($filters['prodi_id'])) {
            $query->where('mahasiswas.prodi_id', $filters['prodi_id']);
        }

        // Filter berdasarkan kelas_perkuliahan_id
        if (!empty($filters['kelas_perkuliahan_id'])) {
            $query->where('mahasiswas.kelas_perkuliahan_id', $filters['kelas_perkuliahan_id']);
        }

        // Filter berdasarkan tingkat (dari kelas_perkuliahan)
        if (!empty($filters['tingkat'])) {
            $query->join('kelas_perkuliahans', 'mahasiswas.kelas_perkuliahan_id', '=', 'kelas_perkuliahans.id')
                  ->where('kelas_perkuliahans.tingkat', $filters['tingkat']);
        }

        // Filter berdasarkan status
        if (!empty($filters['status'])) {
            $query->where('mahasiswas.status', $filters['status']);
        }

        // Filter berdasarkan program studi (string)
        if (!empty($filters['program_studi'])) {
            $query->where('mahasiswas.program_studi', $filters['program_studi']);
        }

        // Filter berdasarkan angkatan (perangkatan/tahun)
        if (!empty($filters['angkatan'])) {
            $query->where('mahasiswas.angkatan', $filters['angkatan']);
        }

        // Filter berdasarkan ID spesifik (untuk filter "mahasiswa_spesifik")
        if (!empty($filters['mahasiswa_ids']) && is_array($filters['mahasiswa_ids'])) {
            $query->whereIn('mahasiswas.id', $filters['mahasiswa_ids']);
        }

        return $query;
    }

    /**
     * Get statistics dari blast email logs
     * 
     * @param string|null $batchId Filter berdasarkan batch ID
     * @return array
     */
    public function getBlastStats(?string $batchId = null): array
    {
        $query = DB::table('email_blast_logs');

        if ($batchId) {
            $query->where('batch_id', $batchId);
        }

        $total = $query->count();
        $success = $query->where('success', true)->count();
        $failed = $total - $success;

        return [
            'total' => $total,
            'success' => $success,
            'failed' => $failed,
            'success_rate' => $total > 0 ? round(($success / $total) * 100, 2) : 0,
        ];
    }

    /**
     * Check rate limit untuk blast email
     * Max 10 blast per jam per user
     * 
     * @param int|null $senderId
     * @return bool
     */
    protected function checkRateLimit(?int $senderId): bool
    {
        if (!$senderId) {
            return true; // Admin tidak ada rate limit
        }

        $key = self::RATE_LIMIT_KEY_PREFIX . $senderId;
        $current = cache()->get($key, 0);

        return $current < self::MAX_BLAST_PER_HOUR;
    }

    /**
     * Increment rate limit counter
     * 
     * @param int|null $senderId
     */
    protected function incrementRateLimit(?int $senderId): void
    {
        if (!$senderId) {
            return;
        }

        $key = self::RATE_LIMIT_KEY_PREFIX . $senderId;
        $current = cache()->get($key, 0);

        // Set TTL 1 jam
        cache()->put($key, $current + 1, 3600);
    }

    /**
     * Get available recipients preview untuk filter tertentu
     * 
     * @param array $filters
     * @return array
     */
    public function getRecipientPreview(array $filters): array
    {
        $query = $this->buildQuery($filters);
        $count = $query->count();
        $sample = $query->take(5)->get(['mahasiswas.nama', 'mahasiswas.email_pribadi']);

        return [
            'total_recipients' => $count,
            'sample' => $sample,
        ];
    }

    /**
     * Kirim credentials blast (email kampus + password) ke mahasiswa
     * 
     * @param array $filters Filter: prodi_id, kelas_perkuliahan_id, tingkat, program_studi, etc
     * @param int|null $senderId User ID yang mengirim
     * @param bool $immediate Kirim langsung atau queue?
     * @return array{success: bool, batch_id: string, total_recipients: int, queued: int}
     */
    public function sendCredentials(
        array $filters = [],
        ?int $senderId = null,
        bool $immediate = false,
        ?string $scheduledAt = null,
        ?string $customSubject = null,
        ?string $customGreeting = null,
        ?string $customMessage = null
    ): array {
        // Check rate limit
        if (!$this->checkRateLimit($senderId)) {
            throw new \RuntimeException('Blast email rate limit exceeded. Coba lagi nanti.');
        }

        // Build query berdasarkan filters
        $query = $this->buildQuery($filters);
        $mahasiswaIds = $query->pluck('mahasiswas.id')->toArray();

        if (empty($mahasiswaIds)) {
            return [
                'success' => true,
                'batch_id' => uniqid('credentials_blast_'),
                'total_recipients' => 0,
                'queued' => 0,
            ];
        }

        $batchId = uniqid('credentials_blast_');
        $queued = 0;

        // Kirim langsung atau masukkan ke Outbox
        if ($immediate) {
            $job = new \App\Jobs\SendCredentialsBlastJob(
                $mahasiswaIds, 
                $senderId,
                $customSubject,
                $customGreeting,
                $customMessage
            );
            dispatch_sync($job);
            $queued = count($mahasiswaIds);
        } else {
            $outboxData = [];
            $now = now();
            $schedule = $scheduledAt ? \Carbon\Carbon::parse($scheduledAt) : $now;
            
            // Get valid emails
            $mahasiswas = Mahasiswa::whereIn('id', $mahasiswaIds)->get(['id', 'email_pribadi', 'email_kampus']);
            
            foreach ($mahasiswas as $m) {
                $target = $m->email_pribadi ?: $m->email_kampus;
                if (!$target) continue;

                $outboxData[] = [
                    'batch_id' => $batchId,
                    'mahasiswa_id' => $m->id,
                    'target_email' => $target,
                    'subject' => $customSubject,
                    'greeting' => $customGreeting,
                    'message_body' => $customMessage,
                    'is_credentials_mode' => true,
                    'status' => 'pending',
                    'scheduled_at' => $schedule,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
            
            foreach (array_chunk($outboxData, 500) as $chunk) {
                \App\Models\EmailOutbox::insert($chunk);
            }
            $queued = count($outboxData);
        }

        // Update rate limit
        $this->incrementRateLimit($senderId);

        Log::info("[CREDENTIALS BLAST] Request baru", [
            'batch_id' => $batchId,
            'recipients' => count($mahasiswaIds),
            'filters' => $filters,
            'sender_id' => $senderId,
            'immediate' => $immediate,
        ]);

        return [
            'success' => true,
            'batch_id' => $batchId,
            'total_recipients' => count($mahasiswaIds),
            'queued' => $queued,
        ];
    }
}
