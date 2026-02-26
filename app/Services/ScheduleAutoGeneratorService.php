<?php

namespace App\Services;

use App\Models\DosenAvailability;
use App\Models\JamPerkuliahan;
use App\Models\JadwalProposal;
use App\Models\Jadwal;
use App\Models\Ruangan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ScheduleAutoGeneratorService
{
    private array $availabilityMap = [];
    private array $fallbackSlots = [];
    private array $jamPerkuliahanMap = [];
    private array $ruangList = [];
    private array $statistics = [
        'total' => 0,
        'within_availability' => 0,
        'outside_availability' => 0,
        'failed' => 0,
        'reasons' => [],
        'dosen_fallbacks' => [] // Track which dosens use fallback: dosen_id => count
    ];

    /**
     * Initialize service with semester context
     */
    public function initialize(int $semesterId): void
    {
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
        Log::info("Loading availabilities for semester_id: {$semesterId}");
        
        $availabilities = DosenAvailability::with('jamPerkuliahan')
            ->where('semester_id', $semesterId)
            ->where('status', 'available')
            ->get();

        Log::info("Found {$availabilities->count()} availability records");

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

        Log::info("Loaded availabilities for " . count($this->availabilityMap) . " dosens");
        
        // Debug: log first 3 dosens with availability
        $sampleDosens = array_slice(array_keys($this->availabilityMap), 0, 3);
        foreach ($sampleDosens as $dosenId) {
            Log::info("Dosen {$dosenId} has " . count($this->availabilityMap[$dosenId]) . " available slots");
        }
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

        // Randomize untuk variasi
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
        // Check if dosen has availability data
        $hasAvailability = isset($this->availabilityMap[$dosenId]) && !empty($this->availabilityMap[$dosenId]);
        
        Log::debug("Getting slots for dosen {$dosenId} (SKS: {$requiredSks})", [
            'has_availability' => $hasAvailability,
            'slot_count' => $hasAvailability ? count($this->availabilityMap[$dosenId]) : 0
        ]);

        if ($hasAvailability) {
            $slots = $this->buildSlotsFromAvailability($dosenId, $requiredSks);
            if (!empty($slots)) {
                Log::info("Using availability slots for dosen {$dosenId}", ['slot_count' => count($slots)]);
                return [
                    'slots' => $slots,
                    'source' => 'availability',
                    'has_availability' => true
                ];
            } else {
                Log::warning("Dosen {$dosenId} has availability but no suitable slots found (SKS: {$requiredSks})");
            }
        }

        // Fallback to general slots
        $fallbackSlots = $this->buildFallbackSlotsForSks($requiredSks);
        Log::info("Using fallback slots for dosen {$dosenId}", ['slot_count' => count($fallbackSlots)]);
        
        return [
            'slots' => $fallbackSlots,
            'source' => 'fallback',
            'has_availability' => $hasAvailability,
            'reason' => $hasAvailability ? 'Ketersediaan dosen tidak mencukupi untuk {$requiredSks} SKS berturut-turut' : 'Dosen tidak mengisi ketersediaan'
        ];
    }

    /**
     * Build slots from dosen availability
     */
    private function buildSlotsFromAvailability(int $dosenId, int $requiredSks): array
    {
        $availSlots = $this->availabilityMap[$dosenId];
        
        if ($requiredSks == 1) {
            // Single slot, return all available slots (shuffled for randomization)
            shuffle($availSlots);
            return $availSlots;
        }

        // Multi-SKS: find consecutive slots
        return $this->findConsecutiveSlotsFromAvailability($availSlots, $requiredSks);
    }

    /**
     * Find consecutive slots from availability for multi-SKS courses
     */
    private function findConsecutiveSlotsFromAvailability(array $availSlots, int $requiredSks): array
    {
        $combinations = [];
        $groupedByHari = [];

        // Group by hari
        foreach ($availSlots as $slot) {
            $groupedByHari[$slot['hari']][] = $slot;
        }

        Log::debug("Finding consecutive slots", [
            'required_sks' => $requiredSks,
            'days_with_slots' => array_keys($groupedByHari),
            'total_slots' => count($availSlots)
        ]);

        // For each day, find consecutive jam_ke
        foreach ($groupedByHari as $hari => $slots) {
            // Sort by jam_ke
            usort($slots, fn($a, $b) => $a['jam_ke'] <=> $b['jam_ke']);
            
            $jamKeList = array_column($slots, 'jam_ke');
            Log::debug("Checking hari: {$hari}", ['jam_ke_available' => $jamKeList]);

            // Try to find consecutive sequences
            for ($i = 0; $i <= count($slots) - $requiredSks; $i++) {
                $isConsecutive = true;
                $sequence = [];

                for ($j = 0; $j < $requiredSks; $j++) {
                    $currentSlot = $slots[$i + $j];
                    $sequence[] = $currentSlot;

                    if ($j < $requiredSks - 1) {
                        $nextSlot = $slots[$i + $j + 1];
                        // Check if jam_ke is consecutive OR time-adjacent (more lenient)
                        $jamKeConsecutive = ($currentSlot['jam_ke'] + 1 === $nextSlot['jam_ke']);
                        $timeAdjacent = ($currentSlot['jam_selesai'] === $nextSlot['jam_mulai']);
                        
                        if (!$jamKeConsecutive && !$timeAdjacent) {
                            $isConsecutive = false;
                            break;
                        }
                    }
                }

                if ($isConsecutive && count($sequence) === $requiredSks) {
                    // Combine into single slot
                    $combo = [
                        'hari' => $hari,
                        'jam_perkuliahan_id' => $sequence[0]['jam_perkuliahan_id'], // Store first
                        'jam_mulai' => $sequence[0]['jam_mulai'],
                        'jam_selesai' => $sequence[count($sequence) - 1]['jam_selesai'],
                        'jam_ke' => $sequence[0]['jam_ke'],
                        'consecutive_ids' => array_column($sequence, 'jam_perkuliahan_id'),
                    ];
                    $combinations[] = $combo;
                    Log::debug("Found consecutive combination", $combo);
                }
            }
        }

        Log::info("Found consecutive combinations from availability", ['count' => count($combinations)]);

        // Shuffle combinations for randomization
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

        // Build consecutive combinations from fallback
        $jamSlots = JamPerkuliahan::where('is_active', true)
            ->orderBy('jam_ke')
            ->get()
            ->toArray();

        $hari = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        $combinations = [];

        Log::debug("Building fallback slots for {$requiredSks} SKS", ['total_jam_slots' => count($jamSlots)]);

        foreach ($hari as $h) {
            for ($i = 0; $i <= count($jamSlots) - $requiredSks; $i++) {
                $isConsecutive = true;
                $sequence = [];

                for ($j = 0; $j < $requiredSks; $j++) {
                    $currentSlot = $jamSlots[$i + $j];
                    $sequence[] = $currentSlot;

                    if ($j < $requiredSks - 1) {
                        $nextSlot = $jamSlots[$i + $j + 1];
                        
                        // More lenient: check jam_ke consecutive OR time adjacent (within 15 min gap)
                        $jamKeConsecutive = ($currentSlot['jam_ke'] + 1 === $nextSlot['jam_ke']);
                        
                        $currentEnd = strtotime($currentSlot['jam_selesai']);
                        $nextStart = strtotime($nextSlot['jam_mulai']);
                        $gapMinutes = ($nextStart - $currentEnd) / 60;
                        $timeAdjacent = ($gapMinutes >= 0 && $gapMinutes <= 15); // Allow 15 min gap
                        
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

        Log::info("Built fallback consecutive combinations", ['count' => count($combinations), 'required_sks' => $requiredSks]);

        shuffle($combinations);
        return $combinations;
    }

    /**
     * Try to assign a class to a time slot
     */
    public function tryAssignClass(object $kmk, array $slotData, string $source): ?array
    {
        $candidates = $slotData['slots'];
        
        $attemptedSlots = 0;
        $conflictCount = 0;
        $noRoomCount = 0;

        foreach ($candidates as $slot) {
            $attemptedSlots++;
            
            // Check all conflicts
            if ($this->hasConflict($kmk, $slot)) {
                $conflictCount++;
                Log::debug("Slot has conflict", [
                    'kelas_id' => $kmk->kelas_id ?? 'N/A',
                    'dosen_id' => $kmk->dosen_id,
                    'hari' => $slot['hari'],
                    'jam' => $slot['jam_mulai'] . '-' . $slot['jam_selesai']
                ]);
                continue;
            }

            // Find available room
            $ruangan = $slot['ruangan'] ?? $this->findAvailableRoom($slot['hari'], $slot['jam_mulai'], $slot['jam_selesai']);
            
            if (!$ruangan) {
                $noRoomCount++;
                Log::debug("No room available for slot", [
                    'hari' => $slot['hari'],
                    'jam' => $slot['jam_mulai'] . '-' . $slot['jam_selesai']
                ]);
                continue; // No room available
            }

            // Determine if outside availability
            $isOutside = ($source === 'fallback');
            $outsideReason = null;

            if ($isOutside) {
                $outsideReason = $slotData['reason'] ?? 'Slot ketersediaan bentrok, pakai fallback';
            }

            Log::info("Successfully assigned class", [
                'kelas_id' => $kmk->kelas_id ?? 'N/A',
                'dosen_id' => $kmk->dosen_id,
                'hari' => $slot['hari'],
                'jam' => $slot['jam_mulai'] . '-' . $slot['jam_selesai'],
                'ruangan' => $ruangan,
                'source' => $source
            ]);

            return [
                'hari' => $slot['hari'],
                'jam_mulai' => $slot['jam_mulai'],
                'jam_selesai' => $slot['jam_selesai'],
                'ruangan' => $ruangan,
                'is_outside_availability' => $isOutside,
                'outside_reason' => $outsideReason,
            ];
        }

        Log::warning("Failed to assign class - no suitable slot found", [
            'kelas_id' => $kmk->kelas_id ?? 'N/A',
            'dosen_id' => $kmk->dosen_id,
            'mata_kuliah_id' => $kmk->mata_kuliah_id ?? 'N/A',
            'attempted_slots' => $attemptedSlots,
            'conflicts' => $conflictCount,
            'no_room' => $noRoomCount,
            'source' => $source
        ]);

        return null; // Failed to assign
    }

    /**
     * Check if there's any conflict for the slot
     */
    private function hasConflict(object $kmk, array $slot): bool
    {
        // Check dosen conflict
        if ($this->hasDosenConflict($kmk->dosen_id, $slot['hari'], $slot['jam_mulai'], $slot['jam_selesai'])) {
            return true;
        }
        
        // Check kelas conflict (same kelas can't have multiple schedules at same time)
        if (isset($kmk->kelas_id) && $this->hasKelasConflict($kmk->kelas_id, $slot['hari'], $slot['jam_mulai'], $slot['jam_selesai'])) {
            return true;
        }
        
        return false;
    }
    
    /**
     * Check kelas conflict
     * Using proper time overlap logic: (start1 < end2) AND (end1 > start2)
     */
    private function hasKelasConflict(int $kelasId, string $hari, string $jamMulai, string $jamSelesai): bool
    {
        // Check existing jadwals
        $existingJadwal = Jadwal::where('kelas_id', $kelasId)
            ->where('hari', $hari)
            ->where('jam_mulai', '<', $jamSelesai)
            ->where('jam_selesai', '>', $jamMulai)
            ->exists();

        // Check existing proposals
        $existingProposal = JadwalProposal::where('kelas_id', $kelasId)
            ->where('hari', $hari)
            ->where('jam_mulai', '<', $jamSelesai)
            ->where('jam_selesai', '>', $jamMulai)
            ->whereIn('status', ['pending_dosen', 'approved_dosen', 'pending_admin', 'approved_admin'])
            ->exists();

        return $existingJadwal || $existingProposal;
    }

    /**
     * Check dosen conflict
     * Using proper time overlap logic: (start1 < end2) AND (end1 > start2)
     */
    private function hasDosenConflict(int $dosenId, string $hari, string $jamMulai, string $jamSelesai): bool
    {
        // Check existing jadwals
        $existingJadwal = DB::table('jadwals')
            ->join('kelas', 'jadwals.kelas_id', '=', 'kelas.id')
            ->where('kelas.dosen_id', $dosenId)
            ->where('jadwals.hari', $hari)
            ->where('jadwals.jam_mulai', '<', $jamSelesai)
            ->where('jadwals.jam_selesai', '>', $jamMulai)
            ->exists();

        // Check existing proposals (include pending_dosen)
        $existingProposal = JadwalProposal::where('dosen_id', $dosenId)
            ->where('hari', $hari)
            ->where('jam_mulai', '<', $jamSelesai)
            ->where('jam_selesai', '>', $jamMulai)
            ->whereIn('status', ['pending_dosen', 'approved_dosen', 'pending_admin', 'approved_admin'])
            ->exists();

        return $existingJadwal || $existingProposal;
    }

    /**
     * Find available room for the slot
     */
    private function findAvailableRoom(string $hari, string $jamMulai, string $jamSelesai): ?string
    {
        $ruangList = $this->ruangList;
        shuffle($ruangList);

        foreach ($ruangList as $ruang) {
            // Check if room is occupied in jadwals (using proper overlap logic)
            $ruangTerpakai = Jadwal::where('hari', $hari)
                ->where('ruangan', $ruang)
                ->where('jam_mulai', '<', $jamSelesai)
                ->where('jam_selesai', '>', $jamMulai)
                ->exists();

            // Check if room is occupied in proposals (include pending_dosen)
            $ruangTerpakaiProposal = JadwalProposal::where('hari', $hari)
                ->where('ruangan', $ruang)
                ->where('jam_mulai', '<', $jamSelesai)
                ->where('jam_selesai', '>', $jamMulai)
                ->whereIn('status', ['pending_dosen', 'approved_dosen', 'pending_admin', 'approved_admin'])
                ->exists();

            if (!$ruangTerpakai && !$ruangTerpakaiProposal) {
                return $ruang;
            }
        }

        return null;
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
            
            // Track which dosen uses fallback
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
        // Add random factor to each class for tie-breaking
        foreach ($classes as $class) {
            $class->_random_order = mt_rand();
        }
        
        usort($classes, function ($a, $b) {
            // Get availability count for each dosen
            $availA = isset($this->availabilityMap[$a->dosen_id]) ? count($this->availabilityMap[$a->dosen_id]) : 999;
            $availB = isset($this->availabilityMap[$b->dosen_id]) ? count($this->availabilityMap[$b->dosen_id]) : 999;

            // Priority 1: Fewer availability = higher priority
            if ($availA !== $availB) {
                return $availA <=> $availB;
            }

            // Priority 2: Higher SKS = higher priority
            $sksA = $a->sks ?? 1;
            $sksB = $b->sks ?? 1;
            if ($sksA !== $sksB) {
                return $sksB <=> $sksA;
            }
            
            // Priority 3: Random order for same priority classes (for variation)
            return $a->_random_order <=> $b->_random_order;
        });

        return $classes;
    }
}
