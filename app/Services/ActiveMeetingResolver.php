<?php

namespace App\Services;

use App\Models\KelasMataKuliah;
use App\Models\Pertemuan;
use Illuminate\Support\Collection;

/**
 * Single source of truth for resolving ordered meeting list per kelas.
 *
 * Generates or retrieves the full meeting schedule:
 *   Pertemuan 1..7 (Kuliah) → UTS → Pertemuan 8..14 (Kuliah) → UAS
 *
 * Extensible: future types (Praktikum, Quiz) can be added by modifying
 * buildMeetingSlots() without touching controllers or views.
 */
class ActiveMeetingResolver
{
    /**
     * Default number of regular kuliah meetings per semester.
     */
    public const DEFAULT_KULIAH_COUNT = 14;

    /**
     * Meeting after which UTS is inserted (after Pertemuan 7).
     */
    public const UTS_AFTER = 7;

    /**
     * Get the fully-ordered meeting list for a KelasMataKuliah.
     * Returns existing Pertemuan records matched to slots, creating missing ones on demand.
     *
     * @param  KelasMataKuliah  $kelasMataKuliah
     * @param  bool  $createMissing  If true, auto-create Pertemuan records that don't exist
     * @return Collection<int, array{slot: int, pertemuan: Pertemuan|null, tipe: string, nomor: int, label: string}>
     */
    public function resolve(KelasMataKuliah $kelasMataKuliah, bool $createMissing = false): Collection
    {
        $slots = $this->buildMeetingSlots($kelasMataKuliah);

        // Load existing pertemuans for this kelas, keyed by "tipe:nomor"
        $existing = Pertemuan::where('kelas_mata_kuliah_id', $kelasMataKuliah->id)
            ->get()
            ->keyBy(fn (Pertemuan $p) => ($p->tipe_pertemuan ?? Pertemuan::TIPE_KULIAH) . ':' . $p->nomor_pertemuan);

        return $slots->map(function (array $slot) use ($kelasMataKuliah, $existing, $createMissing) {
            $key = $slot['tipe'] . ':' . $slot['nomor'];
            $pertemuan = $existing->get($key);

            if (!$pertemuan && $createMissing) {
                $pertemuan = Pertemuan::create([
                    'kelas_mata_kuliah_id' => $kelasMataKuliah->id,
                    'nomor_pertemuan'      => $slot['nomor'],
                    'tipe_pertemuan'       => $slot['tipe'],
                    'topik'                => $slot['label'],
                    'status'               => 'scheduled',
                ]);
            }

            $slot['pertemuan'] = $pertemuan;
            return $slot;
        });
    }

    /**
     * Get a specific meeting by slot number (1-based sequential position).
     */
    public function resolveSlot(KelasMataKuliah $kelasMataKuliah, int $slotNumber): ?array
    {
        $slots = $this->resolve($kelasMataKuliah);
        return $slots->firstWhere('slot', $slotNumber);
    }

    /**
     * Find or create a Pertemuan record for a given tipe + nomor.
     */
    public function findOrCreatePertemuan(
        KelasMataKuliah $kelasMataKuliah,
        string $tipe,
        int $nomor,
        array $extra = []
    ): Pertemuan {
        $defaults = [
            'kelas_mata_kuliah_id' => $kelasMataKuliah->id,
            'nomor_pertemuan'      => $nomor,
            'tipe_pertemuan'       => $tipe,
        ];

        return Pertemuan::firstOrCreate($defaults, array_merge([
            'topik'  => $this->labelFor($tipe, $nomor),
            'status' => 'scheduled',
        ], $extra));
    }

    /**
     * Build the ordered slot list for a kelas.
     * Layout: P1..P7 → UTS → P8..P14 → UAS = 16 total slots.
     *
     * @return Collection<int, array{slot: int, tipe: string, nomor: int, label: string}>
     */
    public function buildMeetingSlots(?KelasMataKuliah $kelasMataKuliah = null): Collection
    {
        $kuliahCount = self::DEFAULT_KULIAH_COUNT;
        $utsAfter    = self::UTS_AFTER;
        $slots       = collect();
        $slotNumber  = 1;

        // First half: Pertemuan 1..7
        for ($i = 1; $i <= $utsAfter; $i++) {
            $slots->push([
                'slot'  => $slotNumber++,
                'tipe'  => Pertemuan::TIPE_KULIAH,
                'nomor' => $i,
                'label' => 'Pertemuan ' . $i,
            ]);
        }

        // UTS slot
        $slots->push([
            'slot'  => $slotNumber++,
            'tipe'  => Pertemuan::TIPE_UTS,
            'nomor' => 1,
            'label' => 'UTS (Ujian Tengah Semester)',
        ]);

        // Second half: Pertemuan 8..14
        for ($i = $utsAfter + 1; $i <= $kuliahCount; $i++) {
            $slots->push([
                'slot'  => $slotNumber++,
                'tipe'  => Pertemuan::TIPE_KULIAH,
                'nomor' => $i,
                'label' => 'Pertemuan ' . $i,
            ]);
        }

        // UAS slot
        $slots->push([
            'slot'  => $slotNumber++,
            'tipe'  => Pertemuan::TIPE_UAS,
            'nomor' => 1,
            'label' => 'UAS (Ujian Akhir Semester)',
        ]);

        return $slots;
    }

    /**
     * Generate display label for a given type and number.
     */
    public function labelFor(string $tipe, int $nomor): string
    {
        return match ($tipe) {
            Pertemuan::TIPE_UTS => 'UTS (Ujian Tengah Semester)',
            Pertemuan::TIPE_UAS => 'UAS (Ujian Akhir Semester)',
            default             => 'Pertemuan ' . $nomor,
        };
    }

    /**
     * Map old sequential meeting number (1-16) to the new tipe+nomor scheme.
     * Useful for backward compatibility with existing presensis.pertemuan column.
     *
     * Slot 1-7  → kuliah:1-7
     * Slot 8    → uts:1
     * Slot 9-15 → kuliah:8-14
     * Slot 16   → uas:1
     */
    public function slotToTipeNomor(int $slotNumber): array
    {
        $slots = $this->buildMeetingSlots();
        $slot = $slots->firstWhere('slot', $slotNumber);

        if ($slot) {
            return ['tipe' => $slot['tipe'], 'nomor' => $slot['nomor']];
        }

        // Fallback: treat as kuliah
        return ['tipe' => Pertemuan::TIPE_KULIAH, 'nomor' => $slotNumber];
    }

    /**
     * Reverse: convert tipe+nomor back to sequential slot number.
     */
    public function tipeNomorToSlot(string $tipe, int $nomor): int
    {
        $slots = $this->buildMeetingSlots();
        $slot = $slots->first(fn ($s) => $s['tipe'] === $tipe && $s['nomor'] === $nomor);

        return $slot['slot'] ?? $nomor;
    }
}
