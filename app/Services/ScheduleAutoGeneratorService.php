<?php

namespace App\Services;

use App\Models\DosenAvailability;
use App\Models\JamPerkuliahan;
use App\Models\JadwalProposal;
use App\Models\Jadwal;
use App\Models\Ruangan;
use App\Models\MataKuliah;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * ScheduleAutoGeneratorService (REFACTORED)
 * 
 * Auto-generate class schedules with intelligent room selection based on:
 * - Course type (mata kuliah tipe)
 * - Room category (kategori ruangan)
 * - Dosen availability
 * - Time conflicts
 * - Room capacity
 * 
 * Uses service layer architecture:
 * - RoomMatcherService: Intelligent room selection
 * - ConflictCheckerService: Conflict validation
 * - SchedulingLogService: Structured logging
 */
class ScheduleAutoGeneratorService
{
    private array $availabilityMap = [];
    private array $fallbackSlots = [];
    private array $jamPerkuliahanMap = [];
    private array $ruangList = [];
    private int $semesterId = 0;
    
    private array $statistics = [
        'total' => 0,
        'within_availability' => 0,
        'outside_availability' => 0,
        'failed' => 0,
        'reasons' => [],
        'dosen_fallbacks' => [],
        'category_matched' => 0,
        'fallback_used' => 0,
    ];

    // Service dependencies - injected via constructor
    private RoomMatcherService $roomMatcher;
    private ConflictCheckerService $conflictChecker;
    private SchedulingLogService $logService;

    public function __construct(
        RoomMatcherService $roomMatcher,
        ConflictCheckerService $conflictChecker,
        SchedulingLogService $logService
    ) {
        $this->roomMatcher = $roomMatcher;
        $this->conflictChecker = $conflictChecker;
        $this->logService = $logService;
    }

    /**
     * Initialize service with semester context
     */
    public function initialize(int $semesterId): void
    {
        $this->semesterId = $semesterId;
        $this->logService->startBatch('Schedule Generation', 0);
        $this->loadAvailabilities($semesterId);
        $this->buildFallbackSlots();
        $this->loadJamPerkuliahanMapping();
        $this->loadRuangList();
    }

    /**
     * Load dosen availabilities for specific semester
     */
    private function loadAvailabilities(int $semesterId): void
    {
        $this->logService->info("Loading availabilities for semester_id: {$semesterId}");
        
        $availabilities = DosenAvailability::with('jamPerkuliahan')
            ->where('semester_id', $semesterId)
            ->where('status', 'available')
            ->get();

        $this->logService->info("Found {$availabilities->count()} availability records");

        foreach ($availabilities as $avail) {
            $dosenId = $avail->dosen_id;
            
            if (!isset($this->availabilityMap[$dosenId])) {
                $this->availabilityMap[$dosenId] = [];
            }

            // Ensure consistent time format
            $jamMulai = $avail->jamPerkuliahan->jam_mulai ?? null;
            $jamSelesai = $avail->jamPerkuliahan->jam_selesai ?? null;
            
            if ($jamMulai) {
                $jamMulai = date('H:i', strtotime($jamMulai));
            }
            if ($jamSelesai) {
                $jamSelesai = date('H:i', strtotime($jamSelesai));
            }

            $this->availabilityMap[$dosenId][] = [
                'hari' => $avail->hari,
                'jam_perkuliahan_id' => $avail->jam_perkuliahan_id,
                'jam_mulai' => $jamMulai,
                'jam_selesai' => $jamSelesai,
                'jam_ke' => $avail->jamPerkuliahan->jam_ke ?? null,
            ];
        }

        $this->logService->info("Loaded availabilities for " . count($this->availabilityMap) . " dosens");
    }

    /**
     * Build fallback slots from all active jam_perkuliahan
     */
    private function buildFallbackSlots(): void
    {
        $hari = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        $jamSlots = JamPerkuliahan::where('is_active', true)
            ->orderBy('jam_ke')
            ->get();

        foreach ($hari as $h) {
            foreach ($jamSlots as $jam) {
                $this->fallbackSlots[] = [
                    'hari' => $h,
                    'jam_perkuliahan_id' => $jam->id,
                    'jam_mulai' => date('H:i', strtotime($jam->jam_mulai)),
                    'jam_selesai' => date('H:i', strtotime($jam->jam_selesai)),
                    'jam_ke' => $jam->jam_ke,
                ];
            }
        }

        shuffle($this->fallbackSlots);
    }

    /**
     * Load jam_perkuliahan mapping for quick lookup
     */
    private function loadJamPerkuliahanMapping(): void
    {
        $jams = JamPerkuliahan::where('is_active', true)->get();
        
        foreach ($jams as $jam) {
            $this->jamPerkuliahanMap[$jam->id] = [
                'jam_ke' => $jam->jam_ke,
                'jam_mulai' => date('H:i', strtotime($jam->jam_mulai)),
                'jam_selesai' => date('H:i', strtotime($jam->jam_selesai)),
            ];
        }
    }

    /**
     * Load active room list
     */
    private function loadRuangList(): void
    {
        $this->ruangList = Ruangan::where('status', 'aktif')
            ->orderBy('kode_ruangan')
            ->pluck('kode_ruangan')
            ->toArray();
    }

    /**
     * Get candidate slots for a class based on dosen availability
     */
    public function getCandidateSlots(int $dosenId, int $requiredSks = 1): array
    {
        $hasAvailability = isset($this->availabilityMap[$dosenId]) && !empty($this->availabilityMap[$dosenId]);
        
        $this->logService->info("Getting slots for dosen {$dosenId} (SKS: {$requiredSks})", [
            'has_availability' => $hasAvailability,
            'slot_count' => $hasAvailability ? count($this->availabilityMap[$dosenId]) : 0
        ]);

        if ($hasAvailability) {
            $slots = $this->buildSlotsFromAvailability($dosenId, $requiredSks);
            if (!empty($slots)) {
                $this->logService->info("Using availability slots for dosen {$dosenId}", ['slot_count' => count($slots)]);
                return [
                    'slots' => $slots,
                    'source' => 'availability',
                    'has_availability' => true
                ];
            }
        }

        // Fallback to general slots
        $fallbackSlots = $this->buildFallbackSlotsForSks($requiredSks);
        $this->logService->info("Using fallback slots for dosen {$dosenId}", ['slot_count' => count($fallbackSlots)]);
        
        return [
            'slots' => $fallbackSlots,
            'source' => 'fallback',
            'has_availability' => $hasAvailability,
            'reason' => $hasAvailability ? "Ketersediaan dosen tidak mencukupi untuk {$requiredSks} SKS berturut-turut" : 'Dosen tidak mengisi ketersediaan'
        ];
    }

    /**
     * Build slots from dosen availability
     */
    private function buildSlotsFromAvailability(int $dosenId, int $requiredSks): array
    {
        $availSlots = $this->availabilityMap[$dosenId];
        
        if ($requiredSks == 1) {
            shuffle($availSlots);
            return $availSlots;
        }

        return $this->findConsecutiveSlotsFromAvailability($availSlots, $requiredSks);
    }

    /**
     * Find consecutive slots from availability for multi-SKS courses
     */
    private function findConsecutiveSlotsFromAvailability(array $availSlots, int $requiredSks): array
    {
        $combinations = [];
        $groupedByHari = [];

        foreach ($availSlots as $slot) {
            $groupedByHari[$slot['hari']][] = $slot;
        }

        foreach ($groupedByHari as $hari => $slots) {
            usort($slots, fn($a, $b) => $a['jam_ke'] <=> $b['jam_ke']);
            
            for ($i = 0; $i <= count($slots) - $requiredSks; $i++) {
                $isConsecutive = true;
                $sequence = [];

                for ($j = 0; $j < $requiredSks; $j++) {
                    $currentSlot = $slots[$i + $j];
                    $sequence[] = $currentSlot;

                    if ($j < $requiredSks - 1) {
                        $nextSlot = $slots[$i + $j + 1];
                        $jamKeConsecutive = ($currentSlot['jam_ke'] + 1 === $nextSlot['jam_ke']);
                        $timeAdjacent = ($currentSlot['jam_selesai'] === $nextSlot['jam_mulai']);
                        
                        if (!$jamKeConsecutive && !$timeAdjacent) {
                            $isConsecutive = false;
                            break;
                        }
                    }
                }

                if ($isConsecutive && count($sequence) === $requiredSks) {
                    $combinations[] = [
                        'hari' => $hari,
                        'jam_perkuliahan_id' => $sequence[0]['jam_perkuliahan_id'],
                        'jam_mulai' => $sequence[0]['jam_mulai'],
                        'jam_selesai' => $sequence[count($sequence) - 1]['jam_selesai'],
                        'jam_ke' => $sequence[0]['jam_ke'],
                        'consecutive_ids' => array_column($sequence, 'jam_perkuliahan_id'),
                    ];
                }
            }
        }

        shuffle($combinations);
        return $combinations;
    }

    /**
     * Build fallback slots for specific SKS
     */
    private function buildFallbackSlotsForSks(int $requiredSks): array
    {
        if ($requiredSks == 1) {
            return $this->fallbackSlots;
        }

        $jamSlots = JamPerkuliahan::where('is_active', true)
            ->orderBy('jam_ke')
            ->get()
            ->toArray();

        $hari = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        $combinations = [];

        foreach ($hari as $h) {
            for ($i = 0; $i <= count($jamSlots) - $requiredSks; $i++) {
                $isConsecutive = true;
                $sequence = [];

                for ($j = 0; $j < $requiredSks; $j++) {
                    $currentSlot = $jamSlots[$i + $j];
                    $sequence[] = $currentSlot;

                    if ($j < $requiredSks - 1) {
                        $nextSlot = $jamSlots[$i + $j + 1];
                        $jamKeConsecutive = ($currentSlot['jam_ke'] + 1 === $nextSlot['jam_ke']);
                        
                        $currentEnd = strtotime($currentSlot['jam_selesai']);
                        $nextStart = strtotime($nextSlot['jam_mulai']);
                        $gapMinutes = ($nextStart - $currentEnd) / 60;
                        $timeAdjacent = ($gapMinutes >= 0 && $gapMinutes <= 15);
                        
                        if (!$jamKeConsecutive && !$timeAdjacent) {
                            $isConsecutive = false;
                            break;
                        }
                    }
                }

                if ($isConsecutive && count($sequence) === $requiredSks) {
                    $combinations[] = [
                        'hari' => $h,
                        'jam_perkuliahan_id' => $sequence[0]['id'],
                        'jam_mulai' => date('H:i', strtotime($sequence[0]['jam_mulai'])),
                        'jam_selesai' => date('H:i', strtotime($sequence[count($sequence) - 1]['jam_selesai'])),
                        'jam_ke' => $sequence[0]['jam_ke'],
                        'consecutive_ids' => array_column($sequence, 'id'),
                    ];
                }
            }
        }

        shuffle($combinations);
        return $combinations;
    }

    /**
     * Try to assign a class to a time slot with CATEGORY-BASED ROOM MATCHING
     * 
     * @param object $kmk - KelasMataKuliah object with mataKuliah relationship
     * @param array $slotData
     * @param string $source - 'availability' or 'fallback'
     * @return array|null
     */
    public function tryAssignClass(object $kmk, array $slotData, string $source): ?array
    {
        $candidates = $slotData['slots'];
        
        $attemptedSlots = 0;
        $conflictCount = 0;
        $noRoomCount = 0;
        $wasRoomCategoryMatched = false;
        $fallbackUsed = false;

        foreach ($candidates as $slot) {
            $attemptedSlots++;
            $this->logService->incrementOperation();
            
            // Check all conflicts using ConflictCheckerService
            if ($this->hasConflict($kmk, $slot)) {
                $conflictCount++;
                continue;
            }

            // Find available room with CATEGORY MATCHING using RoomMatcherService
            // If $kmk doesn't have mataKuliah relationship, load it from DB
            $mataKuliah = $kmk->mataKuliah ?? MataKuliah::find($kmk->mata_kuliah_id);
            
            if (!$mataKuliah) {
                $this->logService->error("Mata kuliah not found for ID: {$kmk->mata_kuliah_id}");
                continue;
            }
            
            $roomResult = $this->findAvailableRoomWithCategory(
                $mataKuliah,
                $kmk->kapasitas ?? 30,
                $slot['hari'],
                $slot['jam_mulai'],
                $slot['jam_selesai']
            );

            if (!$roomResult['room']) {
                $noRoomCount++;
                continue;
            }

            $ruangan = $roomResult['room'];
            $wasRoomCategoryMatched = $roomResult['category_matched'];
            $fallbackUsed = $roomResult['fallback_used'];

            // Determine if outside availability
            $isOutside = ($source === 'fallback');
            $outsideReason = null;

            if ($isOutside) {
                $outsideReason = $slotData['reason'] ?? 'Slot ketersediaan bentrok, pakai fallback';
            }

            $this->logService->logRoomSelection([
                'mata_kuliah_id' => $mataKuliah->id,
                'selected_room' => $ruangan->kode_ruangan,
                'algorithm' => config('scheduling.room_selection_algorithm', 'least_used'),
                'was_fallback' => $fallbackUsed,
            ]);

            // Track statistics
            if ($wasRoomCategoryMatched) {
                $this->statistics['category_matched']++;
            } elseif ($fallbackUsed) {
                $this->statistics['fallback_used']++;
            }

            return [
                'hari' => $slot['hari'],
                'jam_mulai' => $slot['jam_mulai'],
                'jam_selesai' => $slot['jam_selesai'],
                'ruangan' => $ruangan->kode_ruangan,
                'ruangan_id' => $ruangan->id,
                'kategori_matched' => $wasRoomCategoryMatched,
                'fallback_used' => $fallbackUsed,
                'is_outside_availability' => $isOutside,
                'outside_reason' => $outsideReason,
            ];
        }

        // Load mataKuliah if not already loaded via relationship
        $mataKuliahForLog = $kmk->mataKuliah ?? MataKuliah::find($kmk->mata_kuliah_id);
        $mataKuliahTipe = $mataKuliahForLog ? $mataKuliahForLog->tipe : 'unknown';
        
        $this->logService->logSchedulingFailed('No suitable slot found', [
            'kelas_id' => $kmk->kelas_id ?? 'N/A',
            'dosen_id' => $kmk->dosen_id,
            'mata_kuliah_id' => $kmk->mata_kuliah_id ?? 'N/A',
            'mata_kuliah_tipe' => $mataKuliahTipe,
            'attempted_slots' => $attemptedSlots,
            'conflicts' => $conflictCount,
            'no_room' => $noRoomCount,
            'source' => $source
        ]);

        return null;
    }

    /**
     * Find available room with category matching using RoomMatcherService
     * 
     * @return array ['room' => Ruangan|null, 'category_matched' => bool, 'fallback_used' => bool]
     */
    private function findAvailableRoomWithCategory(
        MataKuliah $mataKuliah,
        int $classCapacity,
        string $hari,
        string $jamMulai,
        string $jamSelesai
    ): array {
        // Use RoomMatcherService to get best room with category matching
        $room = $this->roomMatcher->getBestRoom(
            $mataKuliah,
            $hari,
            $jamMulai,
            $jamSelesai,
            $classCapacity
        );

        if ($room) {
            $isCategoryMatched = $this->roomMatcher->isRoomSuitable($room, $mataKuliah);
            return [
                'room' => $room,
                'category_matched' => $isCategoryMatched,
                'fallback_used' => !$isCategoryMatched,
            ];
        }

        // Fallback: linear scan for any available room
        return [
            'room' => $this->findAnyAvailableRoom($hari, $jamMulai, $jamSelesai),
            'category_matched' => false,
            'fallback_used' => true,
        ];
    }

    /**
     * Fallback: find any available room without category matching
     */
    private function findAnyAvailableRoom(string $hari, string $jamMulai, string $jamSelesai): ?Ruangan
    {
        $ruangList = $this->ruangList;
        shuffle($ruangList);

        foreach ($ruangList as $ruangKode) {
            $hasConflict = $this->conflictChecker->hasRoomConflict($ruangKode, $hari, $jamMulai, $jamSelesai);
            
            if (!$hasConflict) {
                return Ruangan::where('kode_ruangan', $ruangKode)->first();
            }
        }

        return null;
    }

    /**
     * Check if there's any conflict for the slot using ConflictCheckerService
     */
    private function hasConflict(object $kmk, array $slot): bool
    {
        // Check dosen conflict
        if ($this->conflictChecker->hasDosenConflict(
            $kmk->dosen_id,
            $slot['hari'],
            $slot['jam_mulai'],
            $slot['jam_selesai']
        )) {
            return true;
        }
        
        // Check kelas conflict
        if (isset($kmk->kelas_id) && $this->hasKelasConflict($kmk->kelas_id, $slot['hari'], $slot['jam_mulai'], $slot['jam_selesai'])) {
            return true;
        }
        
        return false;
    }
    
    /**
     * Check kelas conflict
     */
    private function hasKelasConflict(int $kelasId, string $hari, string $jamMulai, string $jamSelesai): bool
    {
        $existingJadwal = Jadwal::where('kelas_id', $kelasId)
            ->where('hari', $hari)
            ->where('jam_mulai', '<', $jamSelesai)
            ->where('jam_selesai', '>', $jamMulai)
            ->exists();

        $existingProposal = JadwalProposal::where('kelas_id', $kelasId)
            ->where('hari', $hari)
            ->where('jam_mulai', '<', $jamSelesai)
            ->where('jam_selesai', '>', $jamMulai)
            ->whereIn('status', ['pending_dosen', 'approved_dosen', 'pending_admin', 'approved_admin'])
            ->exists();

        return $existingJadwal || $existingProposal;
    }

    /**
     * Update statistics
     */
    public function recordSuccess(bool $isOutside, ?string $reason = null, ?int $dosenId = null): void
    {
        $this->statistics['total']++;
        
        if ($isOutside) {
            $this->statistics['outside_availability']++;
            if ($reason) {
                if (!isset($this->statistics['reasons'][$reason])) {
                    $this->statistics['reasons'][$reason] = 0;
                }
                $this->statistics['reasons'][$reason]++;
            }
            
            if ($dosenId) {
                if (!isset($this->statistics['dosen_fallbacks'][$dosenId])) {
                    $this->statistics['dosen_fallbacks'][$dosenId] = 0;
                }
                $this->statistics['dosen_fallbacks'][$dosenId]++;
            }
        } else {
            $this->statistics['within_availability']++;
        }
    }

    /**
     * Record failure
     */
    public function recordFailure(): void
    {
        $this->statistics['failed']++;
    }

    /**
     * Get generation statistics
     */
    public function getStatistics(): array
    {
        return $this->statistics;
    }

    /**
     * Sort classes by priority (limited availability first, high SKS first)
     */
    public function sortClassesByPriority(array $classes): array
    {
        foreach ($classes as $class) {
            $class->_random_order = mt_rand();
        }
        
        usort($classes, function ($a, $b) {
            $availA = isset($this->availabilityMap[$a->dosen_id]) ? count($this->availabilityMap[$a->dosen_id]) : 999;
            $availB = isset($this->availabilityMap[$b->dosen_id]) ? count($this->availabilityMap[$b->dosen_id]) : 999;

            if ($availA !== $availB) {
                return $availA <=> $availB;
            }

            $sksA = $a->sks ?? 1;
            $sksB = $b->sks ?? 1;
            if ($sksA !== $sksB) {
                return $sksB <=> $sksA;
            }
            
            return $a->_random_order <=> $b->_random_order;
        });

        return $classes;
    }
}