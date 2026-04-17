<?php

namespace App\Services;

use App\Models\Jadwal;
use App\Models\JadwalProposal;
use App\Models\DosenAvailability;
use Illuminate\Support\Facades\Log;

/**
 * ConflictCheckerService
 * 
 * Validates scheduling constraints:
 * - Room time conflicts
 * - Dosen time conflicts
 * - Dosen availability
 * - Class capacity conflicts
 */
class ConflictCheckerService
{
    protected SchedulingLogService $logService;

    public function __construct(SchedulingLogService $logService)
    {
        $this->logService = $logService;
    }

    /**
     * Check if room has time conflict at given slot
     * 
     * @param string $ruanganKode
     * @param string $hari (Monday-Sunday or Senin-Minggu)
     * @param string $jamMulai (HH:MM)
     * @param string $jamSelesai (HH:MM)
     * @return bool
     */
    public function hasRoomConflict(
        string $ruanganKode,
        string $hari,
        string $jamMulai,
        string $jamSelesai
    ): bool {
        // Check existing jadwal
        $jadwalConflict = Jadwal::where('ruangan', $ruanganKode)
            ->where('hari', $hari)
            ->where('jam_mulai', '<', $jamSelesai)
            ->where('jam_selesai', '>', $jamMulai)
            ->exists();

        if ($jadwalConflict) {
            return true;
        }

        // Check pending/approved jadwal proposals
        $proposalConflict = JadwalProposal::where('ruangan', $ruanganKode)
            ->where('hari', $hari)
            ->where('jam_mulai', '<', $jamSelesai)
            ->where('jam_selesai', '>', $jamMulai)
            ->whereIn('status', [
                'pending_dosen',
                'approved_dosen',
                'pending_admin',
                'approved_admin'
            ])
            ->exists();

        return $proposalConflict;
    }

    /**
     * Check if dosen has time conflict at given slot
     * 
     * @param int $dosenId
     * @param string $hari
     * @param string $jamMulai
     * @param string $jamSelesai
     * @return bool
     */
    public function hasDosenConflict(
        int $dosenId,
        string $hari,
        string $jamMulai,
        string $jamSelesai
    ): bool {
        // Check existing jadwal
        $jadwalConflict = Jadwal::whereHas('kelas', function ($q) use ($dosenId) {
            $q->where('dosen_id', $dosenId);
        })
            ->where('hari', $hari)
            ->where('jam_mulai', '<', $jamSelesai)
            ->where('jam_selesai', '>', $jamMulai)
            ->exists();

        if ($jadwalConflict) {
            return true;
        }

        // Check pending/approved jadwal proposals
        $proposalConflict = JadwalProposal::where('dosen_id', $dosenId)
            ->where('hari', $hari)
            ->where('jam_mulai', '<', $jamSelesai)
            ->where('jam_selesai', '>', $jamMulai)
            ->whereIn('status', [
                'pending_dosen',
                'approved_dosen',
                'pending_admin',
                'approved_admin'
            ])
            ->exists();

        return $proposalConflict;
    }

    /**
     * Check if dosen is available at given time
     * 
     * Uses DosenAvailability table if available,
     * otherwise returns true (no availability constraint)
     * 
     * @param int $dosenId
     * @param int $semesterId
     * @param string $hari
     * @param array $jamPerkuliahanIds
     * @return bool
     */
    public function isDosenAvailable(
        int $dosenId,
        int $semesterId,
        string $hari,
        array $jamPerkuliahanIds
    ): bool {
        // Check if dosen availability is tracked
        $availabilityCount = DosenAvailability::where('dosen_id', $dosenId)
            ->where('semester_id', $semesterId)
            ->count();

        // If no availability data, assume available
        if ($availabilityCount === 0) {
            return true;
        }

        // Check each jam_perkuliahan slot
        foreach ($jamPerkuliahanIds as $jamId) {
            $availability = DosenAvailability::where('dosen_id', $dosenId)
                ->where('semester_id', $semesterId)
                ->where('hari', $hari)
                ->where('jam_perkuliahan_id', $jamId)
                ->first();

            // If slot not available, return false
            if (!$availability || $availability->status !== 'available') {
                return false;
            }
        }

        return true;
    }

    /**
     * Check class capacity fit in room
     * 
     * @param int $classCapacity
     * @param int $roomCapacity
     * @return bool
     */
    public function validateCapacity(int $classCapacity, int $roomCapacity): bool
    {
        if (!config('scheduling.capacity.validate', true)) {
            return true;
        }

        if ($roomCapacity >= $classCapacity) {
            return true;
        }

        if (config('scheduling.capacity.allow_exact_fit', true)) {
            return $roomCapacity === $classCapacity;
        }

        return false;
    }

    /**
     * Comprehensive conflict check for scheduling
     * 
     * Returns detailed conflict information
     * 
     * @param array $scheduleData
     * @param int $semesterId
     * @return array ['has_conflict' => bool, 'conflicts' => []]
     */
    public function checkAllConflicts(array $scheduleData, int $semesterId): array
    {
        $conflicts = [];

        // Extract schedule data
        $ruanganKode = $scheduleData['ruangan'] ?? null;
        $dosenId = $scheduleData['dosen_id'] ?? null;
        $hari = $scheduleData['hari'] ?? null;
        $jamMulai = $scheduleData['jam_mulai'] ?? null;
        $jamSelesai = $scheduleData['jam_selesai'] ?? null;
        $jamPerkuliahanIds = $scheduleData['jam_perkuliahan_ids'] ?? [];
        $classCapacity = $scheduleData['class_capacity'] ?? null;
        $roomCapacity = $scheduleData['room_capacity'] ?? null;

        // Check room conflict
        if ($ruanganKode && $hari && $jamMulai && $jamSelesai) {
            if ($this->hasRoomConflict($ruanganKode, $hari, $jamMulai, $jamSelesai)) {
                $conflicts['room_conflict'] = [
                    'type' => 'room_time_conflict',
                    'message' => "Ruangan {$ruanganKode} sudah terpakai pada jam tersebut",
                    'ruangan' => $ruanganKode,
                    'hari' => $hari,
                    'jam_mulai' => $jamMulai,
                    'jam_selesai' => $jamSelesai,
                ];
            }
        }

        // Check dosen conflict
        if ($dosenId && $hari && $jamMulai && $jamSelesai) {
            if ($this->hasDosenConflict($dosenId, $hari, $jamMulai, $jamSelesai)) {
                $conflicts['dosen_conflict'] = [
                    'type' => 'dosen_time_conflict',
                    'message' => "Dosen sudah ada jadwal pada jam tersebut",
                    'dosen_id' => $dosenId,
                    'hari' => $hari,
                    'jam_mulai' => $jamMulai,
                    'jam_selesai' => $jamSelesai,
                ];
            }
        }

        // Check dosen availability
        if ($dosenId && $hari && !empty($jamPerkuliahanIds)) {
            if (!$this->isDosenAvailable($dosenId, $semesterId, $hari, $jamPerkuliahanIds)) {
                $conflicts['availability_conflict'] = [
                    'type' => 'dosen_unavailable',
                    'message' => "Dosen tidak tersedia pada waktu jadwal ini",
                    'dosen_id' => $dosenId,
                    'hari' => $hari,
                ];
            }
        }

        // Check capacity
        if ($classCapacity && $roomCapacity) {
            if (!$this->validateCapacity($classCapacity, $roomCapacity)) {
                $conflicts['capacity_conflict'] = [
                    'type' => 'insufficient_capacity',
                    'message' => "Kapasitas ruangan ({$roomCapacity}) tidak cukup untuk kelas ({$classCapacity})",
                    'class_capacity' => $classCapacity,
                    'room_capacity' => $roomCapacity,
                ];
            }
        }

        return [
            'has_conflict' => count($conflicts) > 0,
            'conflicts' => $conflicts,
        ];
    }

    /**
     * Get detailed conflict information for logging
     * 
     * @param array $conflicts
     * @return array
     */
    public function formatConflictLog(array $conflicts): array
    {
        return [
            'conflict_count' => count($conflicts),
            'conflict_types' => array_keys($conflicts),
            'details' => $conflicts,
        ];
    }
}
