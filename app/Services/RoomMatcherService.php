<?php

namespace App\Services;

use App\Models\Ruangan;
use App\Models\MataKuliah;
use App\Models\Jadwal;
use App\Models\JadwalProposal;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

/**
 * RoomMatcherService
 * 
 * Intelligent room selection based on:
 * - Course type (teori, praktikum, sidang, lab)
 * - Room category (kelas, praktikum, laboratorium, sidang)
 * - Time availability
 * - Capacity
 * - Fallback strategy
 */
class RoomMatcherService
{
    protected SchedulingLogService $logService;
    protected ConflictCheckerService $conflictChecker;

    public function __construct(
        SchedulingLogService $logService,
        ConflictCheckerService $conflictChecker
    ) {
        $this->logService = $logService;
        $this->conflictChecker = $conflictChecker;
    }

    /**
     * Get available rooms for a mata kuliah
     * 
     * Selection flow:
     * 1. Map course type to allowed room categories
     * 2. Find rooms with matching categories that are free at given time
     * 3. Validate capacity
     * 4. If none available, apply fallback strategy
     * 
     * @param MataKuliah $mataKuliah
     * @param string $hari (Monday-Sunday)
     * @param string $jamMulai (HH:MM)
     * @param string $jamSelesai (HH:MM)
     * @param int $requiredCapacity
     * @param array $options
     * @return Collection|null
     */
    public function getAvailableRooms(
        MataKuliah $mataKuliah,
        string $hari,
        string $jamMulai,
        string $jamSelesai,
        int $requiredCapacity,
        array $options = []
    ): ?Collection {
        // Map mata kuliah type to allowed room categories
        $allowedCategories = $this->mapKategori($mataKuliah->tipe);

        if (empty($allowedCategories)) {
            $this->logService->warning('Invalid mata kuliah type', [
                'mata_kuliah_id' => $mataKuliah->id,
                'tipe' => $mataKuliah->tipe,
            ]);
            return null;
        }

        // Find rooms with matching categories
        $rooms = $this->findRoomsByCategories(
            $allowedCategories,
            $hari,
            $jamMulai,
            $jamSelesai,
            $requiredCapacity,
            $options
        );

        // If found rooms with correct category, return them
        if ($rooms && $rooms->count() > 0) {
            $this->logService->info('Rooms found with matching category', [
                'mata_kuliah_id' => $mataKuliah->id,
                'tipe' => $mataKuliah->tipe,
                'kategori' => $allowedCategories,
                'count' => $rooms->count(),
                'rooms' => $rooms->pluck('kode_ruangan')->toArray(),
            ]);
            return $rooms;
        }

        // Apply fallback if enabled
        if (config('scheduling.fallback.enabled', true)) {
            return $this->handleFallback(
                $mataKuliah,
                $hari,
                $jamMulai,
                $jamSelesai,
                $requiredCapacity,
                $allowedCategories,
                $options
            );
        }

        $this->logService->warning('No available rooms found', [
            'mata_kuliah_id' => $mataKuliah->id,
            'tipe' => $mataKuliah->tipe,
            'kategori' => $allowedCategories,
            'hari' => $hari,
            'jam_mulai' => $jamMulai,
            'jam_selesai' => $jamSelesai,
        ]);

        return null;
    }

    /**
     * Get single best room for assignment
     * 
     * @param MataKuliah $mataKuliah
     * @param string $hari
     * @param string $jamMulai
     * @param string $jamSelesai
     * @param int $requiredCapacity
     * @return Ruangan|null
     */
    public function getBestRoom(
        MataKuliah $mataKuliah,
        string $hari,
        string $jamMulai,
        string $jamSelesai,
        int $requiredCapacity
    ): ?Ruangan {
        $rooms = $this->getAvailableRooms(
            $mataKuliah,
            $hari,
            $jamMulai,
            $jamSelesai,
            $requiredCapacity
        );

        if (!$rooms || $rooms->count() === 0) {
            return null;
        }

        // Apply selection algorithm
        $algorithm = config('scheduling.room_selection_algorithm', 'least_used');

        return match ($algorithm) {
            'random' => $rooms->random(),
            'first_available' => $rooms->first(),
            'least_used' => $this->selectLeastUsedRoom($rooms),
            'best_fit' => $this->selectBestFitRoom($rooms, $requiredCapacity),
            default => $rooms->first(),
        };
    }

    /**
     * Map mata kuliah type to allowed room categories
     * 
     * @param string $tipe
     * @return array
     */
    private function mapKategori(string $tipe): array
    {
        $mapping = config('scheduling.room_matching', []);

        return $mapping[$tipe]['categories'] ?? [];
    }

    /**
     * Find rooms with specific categories available at given time
     * 
     * @param array $categories
     * @param string $hari
     * @param string $jamMulai
     * @param string $jamSelesai
     * @param int $requiredCapacity
     * @param array $options
     * @return Collection
     */
    private function findRoomsByCategories(
        array $categories,
        string $hari,
        string $jamMulai,
        string $jamSelesai,
        int $requiredCapacity,
        array $options = []
    ): Collection {
        $query = Ruangan::query()
            ->whereHas('kategori', function ($q) use ($categories) {
                $q->whereIn('nama_kategori', $categories);
            })
            ->where('status', 'aktif');

        // Filter by capacity if validation enabled
        if (config('scheduling.capacity.validate', true)) {
            $minCapacity = $this->calculateMinCapacity($requiredCapacity);
            $query->where('kapasitas', '>=', $minCapacity);
        }

        $rooms = $query->get();

        // Filter out rooms with time conflicts
        $availableRooms = $rooms->filter(function (Ruangan $ruangan) use ($hari, $jamMulai, $jamSelesai) {
            return !$this->conflictChecker->hasRoomConflict(
                $ruangan->kode_ruangan,
                $hari,
                $jamMulai,
                $jamSelesai
            );
        });

        return $availableRooms;
    }

    /**
     * Handle fallback when no room with correct category available
     * 
     * @param MataKuliah $mataKuliah
     * @param string $hari
     * @param string $jamMulai
     * @param string $jamSelesai
     * @param int $requiredCapacity
     * @param array $preferredCategories
     * @param array $options
     * @return Collection|null
     */
    private function handleFallback(
        MataKuliah $mataKuliah,
        string $hari,
        string $jamMulai,
        string $jamSelesai,
        int $requiredCapacity,
        array $preferredCategories,
        array $options = []
    ): ?Collection {
        $fallbackOrder = config('scheduling.fallback.fallback_order', []);

        // Try categories in fallback order
        foreach ($fallbackOrder as $category) {
            if (in_array($category, $preferredCategories)) {
                continue; // Already tried this category
            }

            $rooms = $this->findRoomsByCategories(
                [$category],
                $hari,
                $jamMulai,
                $jamSelesai,
                $requiredCapacity,
                $options
            );

            if ($rooms && $rooms->count() > 0) {
                // Log fallback usage
                $this->logService->warning('Room fallback applied', [
                    'mata_kuliah_id' => $mataKuliah->id,
                    'tipe' => $mataKuliah->tipe,
                    'preferred_kategori' => $preferredCategories,
                    'fallback_kategori' => [$category],
                    'room_count' => $rooms->count(),
                    'hari' => $hari,
                    'jam_mulai' => $jamMulai,
                    'jam_selesai' => $jamSelesai,
                ]);

                return $rooms;
            }
        }

        // Last resort: any active room
        $strategy = config('scheduling.fallback.strategy', 'any');
        if ($strategy === 'any') {
            $rooms = $this->findRoomsByCategories(
                $fallbackOrder,
                $hari,
                $jamMulai,
                $jamSelesai,
                $requiredCapacity,
                $options
            );

            if ($rooms && $rooms->count() > 0) {
                $this->logService->warning('Room fallback: using any available room', [
                    'mata_kuliah_id' => $mataKuliah->id,
                    'tipe' => $mataKuliah->tipe,
                    'preferred_kategori' => $preferredCategories,
                    'room_count' => $rooms->count(),
                ]);

                return $rooms;
            }
        }

        return null;
    }

    /**
     * Calculate minimum capacity based on configuration ratio
     * 
     * @param int $requiredCapacity
     * @return int
     */
    private function calculateMinCapacity(int $requiredCapacity): int
    {
        $minRatio = config('scheduling.capacity.min_ratio', 1.1);
        return (int) ceil($requiredCapacity * $minRatio);
    }

    /**
     * Select room with least scheduled classes (distribution strategy)
     * 
     * @param Collection $rooms
     * @return Ruangan
     */
    private function selectLeastUsedRoom(Collection $rooms): Ruangan
    {
        return $rooms->sortBy(function (Ruangan $room) {
            return Jadwal::where('ruangan', $room->kode_ruangan)->count() +
                   JadwalProposal::where('ruangan', $room->kode_ruangan)
                       ->whereIn('status', ['pending_dosen', 'approved_dosen', 'pending_admin', 'approved_admin'])
                       ->count();
        })->first();
    }

    /**
     * Select room closest to required capacity (efficiency strategy)
     * 
     * @param Collection $rooms
     * @param int $requiredCapacity
     * @return Ruangan
     */
    private function selectBestFitRoom(Collection $rooms, int $requiredCapacity): Ruangan
    {
        return $rooms->sortBy(function (Ruangan $room) use ($requiredCapacity) {
            return abs($room->kapasitas - $requiredCapacity);
        })->first();
    }

    /**
     * Validate room is suitable for mata kuliah
     * 
     * @param Ruangan $ruangan
     * @param MataKuliah $mataKuliah
     * @return bool
     */
    public function isRoomSuitable(Ruangan $ruangan, MataKuliah $mataKuliah): bool
    {
        $allowedCategories = $this->mapKategori($mataKuliah->tipe);

        if (!$ruangan->kategori) {
            return false; // Room has no category assigned
        }

        return in_array($ruangan->kategori->nama_kategori, $allowedCategories);
    }
}
