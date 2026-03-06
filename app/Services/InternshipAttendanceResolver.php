<?php

namespace App\Services;

use App\Models\Internship;
use App\Models\Krs;
use App\Models\Presensi;

/**
 * Resolves attendance for MK konversi magang.
 *
 * Rules:
 * 1. If a KRS entry has is_internship_conversion = true AND the internship is ONGOING
 *    → Mahasiswa is ALWAYS considered HADIR (100% attendance), no actual Presensi rows needed.
 * 2. For normal MK (is_internship_conversion = false) → normal attendance logic.
 *
 * This service provides helpers for attendance views (dosen + admin) to resolve presence.
 */
class InternshipAttendanceResolver
{
    /**
     * Check if a KRS entry is an internship conversion course with active internship.
     */
    public function isAutoPresent(Krs $krs): bool
    {
        if (!$krs->is_internship_conversion || !$krs->internship_id) {
            return false;
        }

        $internship = $krs->internship ?? Internship::find($krs->internship_id);

        return $internship && $internship->isOngoing();
    }

    /**
     * Resolve attendance status for a specific mahasiswa + pertemuan.
     * Returns 'hadir' if auto-present, or the actual Presensi status if exists, or 'belum' otherwise.
     */
    public function resolveStatus(int $krsId, int $pertemuan): string
    {
        $krs = Krs::find($krsId);
        if (!$krs) return 'belum';

        if ($this->isAutoPresent($krs)) {
            return 'hadir';
        }

        $presensi = Presensi::where('krs_id', $krsId)
            ->where('pertemuan', $pertemuan)
            ->first();

        return $presensi?->status ?? 'belum';
    }

    /**
     * Get attendance summary for a kelas_mata_kuliah_id.
     * Returns an array keyed by mahasiswa_id with attendance info.
     * Internship conversion students are marked as auto-present.
     */
    public function getClassAttendanceSummary(int $kelasMataKuliahId, int $totalPertemuan): array
    {
        $krsEntries = Krs::where('kelas_mata_kuliah_id', $kelasMataKuliahId)
            ->whereIn('status', ['approved', 'disetujui'])
            ->with(['mahasiswa.user'])
            ->get();

        $summary = [];

        foreach ($krsEntries as $krs) {
            $isAutoPresent = $this->isAutoPresent($krs);

            if ($isAutoPresent) {
                $summary[$krs->mahasiswa_id] = [
                    'nama'           => $krs->mahasiswa?->user?->name ?? '-',
                    'nim'            => $krs->mahasiswa?->nim ?? '-',
                    'total_hadir'    => $totalPertemuan,
                    'total_alfa'     => 0,
                    'total_izin'     => 0,
                    'total_sakit'    => 0,
                    'persentase'     => 100.0,
                    'is_internship'  => true,
                    'internship_instansi' => Internship::find($krs->internship_id)?->instansi ?? '-',
                ];
            } else {
                $presensis = Presensi::where('krs_id', $krs->id)->get();
                $hadir = $presensis->where('status', 'hadir')->count();
                $alfa  = $presensis->where('status', 'alfa')->count();
                $izin  = $presensis->where('status', 'izin')->count();
                $sakit = $presensis->where('status', 'sakit')->count();

                $summary[$krs->mahasiswa_id] = [
                    'nama'           => $krs->mahasiswa?->user?->name ?? '-',
                    'nim'            => $krs->mahasiswa?->nim ?? '-',
                    'total_hadir'    => $hadir,
                    'total_alfa'     => $alfa,
                    'total_izin'     => $izin,
                    'total_sakit'    => $sakit,
                    'persentase'     => $totalPertemuan > 0 ? round(($hadir / $totalPertemuan) * 100, 1) : 0,
                    'is_internship'  => false,
                    'internship_instansi' => null,
                ];
            }
        }

        return $summary;
    }
}
