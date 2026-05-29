<?php

namespace App\Domain\Wisuda\Services;

use App\Domain\Skripsi\Enums\SkripsiStatus;
use App\Domain\Wisuda\Enums\WisudaRegistrationStatus;
use App\Models\Mahasiswa;
use App\Models\SkripsiSubmission;
use App\Models\WisudaRegistration;
use App\Models\Invoice;
use App\Models\Pembayaran;
use App\Models\Krs;

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
            && ! $this->hasActiveRegistration($mahasiswa)
            && empty($this->getUnpaidSemesters($mahasiswa));
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
        $unpaidSemesters    = $this->getUnpaidSemesters($mahasiswa);
        $isEligible         = $submission !== null && $activeRegistration === null && empty($unpaidSemesters);

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
            'unpaid_semesters'        => $unpaidSemesters,
        ];
    }

    /**
     * Get semesters that have unpaid invoices or legacy payments.
     * Checks semesters from 1 up to the student's current semester.
     *
     * @return array Array of unpaid semester numbers (e.g. [1, 2, 8])
     */
    public function getUnpaidSemesters(Mahasiswa $mahasiswa): array
    {
        $currentSemester = $mahasiswa->getCurrentSemester();
        $unpaid = [];

        for ($i = 1; $i <= $currentSemester; $i++) {
            // 1. Check new system invoice
            $invoice = Invoice::where('student_id', $mahasiswa->id)
                ->where('semester', $i)
                ->first();

            if ($invoice) {
                if ($invoice->status !== 'LUNAS') {
                    $unpaid[] = $i;
                }
                continue; // Skip legacy check if new invoice exists for this semester
            }

            // 2. Check legacy system payment
            $legacyPayment = Pembayaran::where('mahasiswa_id', $mahasiswa->id)
                ->whereHas('semester', function ($q) use ($i) {
                    $q->where('nama_semester', 'like', "%{$i}%");
                })
                ->first();

            if ($legacyPayment) {
                if ($legacyPayment->status !== 'lunas') {
                    $unpaid[] = $i;
                }
            } else {
                // If neither invoice nor legacy payment exists, check if they enrolled (KRS existed)
                $semesterKodeId = 'sms' . $i;
                $hasKrs = Krs::where('mahasiswa_id', $mahasiswa->id)
                    ->whereHas('mataKuliah', function ($q) use ($semesterKodeId) {
                        $q->where('kode_id', $semesterKodeId);
                    })
                    ->exists();

                if ($hasKrs) {
                    $unpaid[] = $i;
                }
            }
        }

        return $unpaid;
    }
}
