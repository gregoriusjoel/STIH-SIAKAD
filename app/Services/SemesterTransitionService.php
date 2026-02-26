<?php

namespace App\Services;

use App\Models\Semester;
use App\Models\Mahasiswa;
use App\Models\ActivityLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SemesterTransitionService
{
    /**
     * Process semester transition automatically
     * 
     * NOTE: This now respects grace period - old semester is NOT deactivated immediately
     * It stays active for 14 days grace period before being deactivated
     * Use SemesterService::processAutomaticStatusUpdates() for daily status checks
     * 
     * @return array
     */
    public function processTransition(): array
    {
        try {
            DB::beginTransaction();

            // 1. Get active semester
            $activeSemester = $this->getActiveSemester();
            
            if (!$activeSemester) {
                return [
                    'success' => false,
                    'message' => 'Tidak ada semester aktif ditemukan',
                    'data' => null
                ];
            }

            // 2. Check if semester period has ended
            if (!$this->isSemesterEnded($activeSemester)) {
                return [
                    'success' => true,
                    'message' => 'Semester masih berjalan. Belum saatnya transisi.',
                    'data' => [
                        'current_semester' => $activeSemester->nama_semester,
                        'tahun_ajaran' => $activeSemester->tahun_ajaran,
                        'tanggal_selesai' => $activeSemester->tanggal_selesai->format('Y-m-d'),
                        'days_remaining' => Carbon::now()->diffInDays($activeSemester->tanggal_selesai, false)
                    ]
                ];
            }

            // 3. Find next semester
            $nextSemester = $this->findNextSemester($activeSemester);
            
            if (!$nextSemester) {
                return [
                    'success' => false,
                    'message' => 'Semester berikutnya belum tersedia. Mohon tambahkan periode semester baru.',
                    'data' => null
                ];
            }

            // 4. DO NOT deactivate old semester immediately - respect grace period
            // Old semester will be deactivated automatically after grace period by UpdateSemesterStatus command
            Log::info("Old semester {$activeSemester->nama_semester} will remain visible for 14 days grace period");

            // 5. Activate new semester
            $this->activateSemester($nextSemester);

            // 6. Increment mahasiswa semester
            $updatedCount = $this->incrementMahasiswaSemester($nextSemester);

            // 7. Log activity
            $this->logTransition($activeSemester, $nextSemester, $updatedCount);

            DB::commit();

            return [
                'success' => true,
                'message' => 'Transisi semester berhasil dilakukan. Semester lama masih aktif untuk grace period 14 hari.',
                'data' => [
                    'old_semester' => "{$activeSemester->nama_semester} {$activeSemester->tahun_ajaran}",
                    'new_semester' => "{$nextSemester->nama_semester} {$nextSemester->tahun_ajaran}",
                    'mahasiswa_updated' => $updatedCount,
                    'transition_date' => Carbon::now()->format('Y-m-d H:i:s'),
                    'grace_period_info' => "Kelas semester lama tetap tampil hingga " . 
                        Carbon::parse($activeSemester->tanggal_selesai)->addDays(14)->format('Y-m-d')
                ]
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Semester Transition Error: ' . $e->getMessage());
            
            return [
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
                'data' => null
            ];
        }
    }

    /**
     * Get currently active semester
     */
    protected function getActiveSemester(): ?Semester
    {
        return Semester::where('is_active', true)->first();
    }

    /**
     * Check if semester period has ended
     */
    protected function isSemesterEnded(Semester $semester): bool
    {
        return Carbon::now()->greaterThan($semester->tanggal_selesai);
    }

    /**
     * Find next semester based on start date
     */
    protected function findNextSemester(Semester $currentSemester): ?Semester
    {
        return Semester::where('tanggal_mulai', '>', $currentSemester->tanggal_selesai)
            ->orderBy('tanggal_mulai', 'asc')
            ->first();
    }

    /**
     * Deactivate semester
     */
    protected function deactivateSemester(Semester $semester): void
    {
        $semester->update([
            'is_active' => false,
            'status' => 'non-aktif',
            'krs_dapat_diisi' => false
        ]);
    }

    /**
     * Activate semester
     */
    protected function activateSemester(Semester $semester): void
    {
        // Ensure only one semester is active
        Semester::where('is_active', true)->update(['is_active' => false]);
        
        $semester->update([
            'is_active' => true,
            'status' => 'aktif'
        ]);
    }

    /**
     * Increment semester for all eligible mahasiswa
     */
    protected function incrementMahasiswaSemester(Semester $newSemester): int
    {
        $mahasiswas = Mahasiswa::where('status', 'aktif')
            ->where(function($query) use ($newSemester) {
                $query->whereNull('last_semester_id')
                    ->orWhere('last_semester_id', '!=', $newSemester->id);
            })
            ->get();

        $count = 0;
        foreach ($mahasiswas as $mahasiswa) {
            // Don't increment beyond semester 8 (or your max semester)
            $newSemesterValue = min($mahasiswa->semester + 1, 14);
            
            $mahasiswa->update([
                'semester' => $newSemesterValue,
                'last_semester_id' => $newSemester->id
            ]);
            
            $count++;
        }

        return $count;
    }

    /**
     * Log transition activity
     */
    protected function logTransition(Semester $oldSemester, Semester $newSemester, int $updatedCount): void
    {
        try {
            ActivityLog::create([
                'log_name' => 'semester_transition',
                'description' => "Transisi semester otomatis: {$oldSemester->nama_semester} {$oldSemester->tahun_ajaran} → {$newSemester->nama_semester} {$newSemester->tahun_ajaran}",
                'subject_type' => Semester::class,
                'subject_id' => $newSemester->id,
                'causer_type' => 'System',
                'causer_id' => null,
                'properties' => json_encode([
                    'old_semester_id' => $oldSemester->id,
                    'new_semester_id' => $newSemester->id,
                    'mahasiswa_updated' => $updatedCount,
                    'transition_date' => Carbon::now()->toDateTimeString()
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (\Exception $e) {
            Log::warning('Failed to create activity log: ' . $e->getMessage());
        }
    }

    /**
     * Get transition status information
     */
    public function getTransitionStatus(): array
    {
        $activeSemester = $this->getActiveSemester();
        
        if (!$activeSemester) {
            return [
                'has_active_semester' => false,
                'message' => 'Tidak ada semester aktif'
            ];
        }

        $now = Carbon::now();
        $endDate = Carbon::parse($activeSemester->tanggal_selesai);
        $daysRemaining = $now->diffInDays($endDate, false);
        $isEnded = $now->greaterThan($endDate);

        $nextSemester = $this->findNextSemester($activeSemester);

        return [
            'has_active_semester' => true,
            'current_semester' => [
                'id' => $activeSemester->id,
                'nama_semester' => $activeSemester->nama_semester,
                'tahun_ajaran' => $activeSemester->tahun_ajaran,
                'tanggal_mulai' => $activeSemester->tanggal_mulai->format('Y-m-d'),
                'tanggal_selesai' => $activeSemester->tanggal_selesai->format('Y-m-d')
            ],
            'is_ended' => $isEnded,
            'days_remaining' => $daysRemaining,
            'next_semester' => $nextSemester ? [
                'id' => $nextSemester->id,
                'nama_semester' => $nextSemester->nama_semester,
                'tahun_ajaran' => $nextSemester->tahun_ajaran,
                'tanggal_mulai' => $nextSemester->tanggal_mulai->format('Y-m-d')
            ] : null,
            'ready_for_transition' => $isEnded && $nextSemester !== null
        ];
    }
}
