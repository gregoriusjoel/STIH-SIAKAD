<?php

namespace App\Domain\Wisuda\Services;

use App\Models\EmailOutbox;
use App\Models\Mahasiswa;
use App\Models\WisudaBatch;
use App\Models\WisudaRegistration;
use Illuminate\Support\Facades\Log;

/**
 * Sends wisuda-related notifications via the existing EmailOutbox mechanism.
 */
class WisudaNotificationService
{
    /**
     * Notify all mahasiswa assigned to a batch about their schedule.
     */
    public function notifyScheduled(WisudaBatch $batch, array $registrationIds): void
    {
        $registrations = WisudaRegistration::whereIn('id', $registrationIds)
            ->with('mahasiswa')
            ->get();

        $this->sendBatchNotification($batch, $registrations, 'Jadwal Wisuda Anda');
    }

    /**
     * Re-notify all mahasiswa in a batch when the batch is updated.
     */
    public function notifyBatchUpdated(WisudaBatch $batch): void
    {
        $registrations = $batch->registrations()
            ->where('status', 'scheduled')
            ->with('mahasiswa')
            ->get();

        if ($registrations->isEmpty()) {
            return;
        }

        $this->sendBatchNotification($batch, $registrations, 'Perubahan Jadwal Wisuda');
    }

    /**
     * Build and insert email outbox records for batch notification.
     */
    private function sendBatchNotification(
        WisudaBatch $batch,
        $registrations,
        string $subject
    ): void {
        $batchId    = uniqid('wisuda_notif_');
        $now        = now();
        $outboxData = [];

        $messageBody = "Berikut jadwal wisuda Anda:\n\n"
            . "Batch: {$batch->nama_batch}\n"
            . "Tanggal: {$batch->tanggal->translatedFormat('l, d F Y')}\n"
            . "Waktu: {$batch->waktu_mulai->format('H:i')} WIB\n"
            . "Lokasi: {$batch->lokasi}\n";

        if ($batch->catatan) {
            $messageBody .= "\nCatatan: {$batch->catatan}";
        }

        foreach ($registrations as $reg) {
            $mahasiswa = $reg->mahasiswa;
            if (! $mahasiswa) {
                continue;
            }

            $targetEmail = $reg->email_aktif
                ?? $mahasiswa->getActiveEmail()
                ?? $mahasiswa->email_pribadi
                ?? $mahasiswa->email_kampus;

            if (! $targetEmail) {
                continue;
            }

            $outboxData[] = [
                'batch_id'           => $batchId,
                'mahasiswa_id'       => $mahasiswa->id,
                'target_email'       => $targetEmail,
                'subject'            => $subject,
                'greeting'           => 'Yth. ' . ($mahasiswa->user?->name ?? 'Mahasiswa'),
                'message_body'       => $messageBody,
                'is_credentials_mode'=> false,
                'status'             => 'pending',
                'scheduled_at'       => $now,
                'created_at'         => $now,
                'updated_at'         => $now,
            ];
        }

        if (! empty($outboxData)) {
            foreach (array_chunk($outboxData, 500) as $chunk) {
                EmailOutbox::insert($chunk);
            }

            Log::info('[WISUDA NOTIFICATION] Sent', [
                'batch_id'   => $batchId,
                'batch_name' => $batch->nama_batch,
                'recipients' => count($outboxData),
            ]);
        }
    }
}
