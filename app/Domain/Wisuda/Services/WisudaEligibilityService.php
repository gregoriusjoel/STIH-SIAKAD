<?php

namespace App\Domain\Wisuda\Services;

use App\Domain\Skripsi\Enums\SkripsiStatus;
use App\Domain\Wisuda\Enums\WisudaRegistrationStatus;
use App\Models\Mahasiswa;
use App\Models\SkripsiSubmission;
use App\Models\WisudaRegistration;

class WisudaEligibilityService
{
    /**
     * Check whether the mahasiswa is eligible to register for wisuda.
     *
     * Requirements:
     * 1. Has a skripsi_submission with status REVISION_APPROVED
     * 2. Does not have an active wisuda_registration (pending / approved / scheduled)
     */
    public function isEligible(Mahasiswa $mahasiswa): bool
    {
        return $this->getEligibleSubmission($mahasiswa) !== null
            && ! $this->hasActiveRegistration($mahasiswa);
    }

    /**
     * Get the skripsi submission that makes this student eligible.
     */
    public function getEligibleSubmission(Mahasiswa $mahasiswa): ?SkripsiSubmission
    {
        return SkripsiSubmission::where('mahasiswa_id', $mahasiswa->id)
            ->whereIn('status', [
                SkripsiStatus::REVISION_APPROVED,
                SkripsiStatus::SKRIPSI_COMPLETED,
            ])
            ->latest()
            ->first();
    }

    /**
     * Check if the student already has a non-rejected registration.
     */
    public function hasActiveRegistration(Mahasiswa $mahasiswa): bool
    {
        $activeStatuses = array_map(
            fn($s) => $s->value,
            WisudaRegistrationStatus::activeStatuses()
        );

        return WisudaRegistration::where('mahasiswa_id', $mahasiswa->id)
            ->whereIn('status', $activeStatuses)
            ->exists();
    }

    /**
     * Get the current active registration (if any).
     */
    public function getActiveRegistration(Mahasiswa $mahasiswa): ?WisudaRegistration
    {
        $activeStatuses = array_map(
            fn($s) => $s->value,
            WisudaRegistrationStatus::activeStatuses()
        );

        return WisudaRegistration::where('mahasiswa_id', $mahasiswa->id)
            ->whereIn('status', $activeStatuses)
            ->with(['documents', 'batch', 'skripsiSubmission'])
            ->latest()
            ->first();
    }

    /**
     * Get a summary for the wisuda index page.
     */
    public function getSummary(Mahasiswa $mahasiswa): array
    {
        $submission         = $this->getEligibleSubmission($mahasiswa);
        $activeRegistration = $this->getActiveRegistration($mahasiswa);
        $isEligible         = $submission !== null && $activeRegistration === null;

        // Also fetch past rejected registrations for history
        $rejectedRegistrations = WisudaRegistration::where('mahasiswa_id', $mahasiswa->id)
            ->where('status', WisudaRegistrationStatus::REJECTED)
            ->orderByDesc('created_at')
            ->get();

        return [
            'submission'              => $submission,
            'active_registration'     => $activeRegistration,
            'is_eligible'             => $isEligible,
            'rejected_registrations'  => $rejectedRegistrations,
        ];
    }
}
