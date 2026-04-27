<?php

namespace App\Services;

use App\Domain\Skripsi\Enums\SkripsiStatus;
use App\Models\DosenAvailabilityCheck;
use App\Models\Internship;
use App\Models\JadwalProposal;
use App\Models\JadwalReschedule;
use App\Models\KelasReschedule;
use App\Models\Krs;
use App\Models\Pengajuan;
use App\Models\SkripsiSidangRegistration;
use App\Models\SkripsiSubmission;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class NotificationService
{
    /**
     * Get all admin notifications
     * 
     * @param bool $includeHandled Whether to include notifications that have already been processed
     * @param int $limit Maximum number of notifications to return
     * @return Collection
     */
    public function getAdminNotifications(bool $includeHandled = false, int $limit = 50): Collection
    {
        $notifications = collect();
        $safeHumanTime = function ($time) {
            try {
                return Carbon::parse($time)->locale('id')->diffForHumans();
            } catch (\Throwable $e) {
                return '-';
            }
        };

        // 1) Pengajuan Surat
        try {
            $query = Pengajuan::with('mahasiswa.user')->orderByDesc('updated_at');
            if (!$includeHandled) {
                $query->where('status', Pengajuan::STATUS_SUBMITTED);
            }
            $pengajuans = $query->limit($limit)->get();

            foreach ($pengajuans as $pengajuan) {
                $when = $pengajuan->submitted_at ?? $pengajuan->updated_at ?? $pengajuan->created_at;
                $notifications->push([
                    'id' => 'pengajuan-' . $pengajuan->id,
                    'title' => 'Pengajuan Surat',
                    'message' => ($pengajuan->mahasiswa?->user?->name ?? 'Mahasiswa') . ' mengajukan ' . ($pengajuan->jenis_label ?? 'surat') . '.',
                    'icon' => 'description',
                    'url' => route('admin.pengajuan.show', $pengajuan),
                    'human_time' => $safeHumanTime($when),
                    'created_at_ts' => Carbon::parse($when)->timestamp,
                    'needs_action' => $pengajuan->status === Pengajuan::STATUS_SUBMITTED,
                ]);
            }
        } catch (\Throwable $e) {}

        // 2) Proposal Skripsi
        try {
            $query = SkripsiSubmission::with('mahasiswa.user')->orderByDesc('updated_at');
            if (!$includeHandled) {
                $query->where('status', SkripsiStatus::PROPOSAL_SUBMITTED);
            } else {
                $query->whereIn('status', [SkripsiStatus::PROPOSAL_SUBMITTED, SkripsiStatus::PROPOSAL_APPROVED, SkripsiStatus::PROPOSAL_REJECTED]);
            }
            $skripsis = $query->limit($limit)->get();

            foreach ($skripsis as $skripsi) {
                $when = $skripsi->updated_at ?? $skripsi->created_at;
                $notifications->push([
                    'id' => 'skripsi-proposal-' . $skripsi->id,
                    'title' => 'Proposal Skripsi',
                    'message' => ($skripsi->mahasiswa?->user?->name ?? 'Mahasiswa') . ' mengirim proposal skripsi.',
                    'icon' => 'fact_check',
                    'url' => route('admin.skripsi.index', ['tab' => 'proposal']),
                    'human_time' => $safeHumanTime($when),
                    'created_at_ts' => Carbon::parse($when)->timestamp,
                    'needs_action' => $skripsi->status === SkripsiStatus::PROPOSAL_SUBMITTED,
                ]);
            }
        } catch (\Throwable $e) {}

        // 3) Pendaftaran Sidang
        try {
            $query = SkripsiSidangRegistration::with(['submission.mahasiswa.user'])->orderByDesc('updated_at');
            if (!$includeHandled) {
                $query->whereIn('status', ['submitted', 'SUBMITTED']);
            } else {
                $query->whereIn('status', ['submitted', 'verified', 'SUBMITTED', 'VERIFIED', 'rejected', 'REJECTED']);
            }
            $sidangs = $query->limit($limit)->get();

            foreach ($sidangs as $reg) {
                $when = $reg->submitted_at ?? $reg->updated_at ?? $reg->created_at;
                $status = strtolower((string) $reg->status);
                $statusText = $status === 'verified' ? 'terverifikasi' : ($status === 'rejected' ? 'ditolak' : 'mengajukan pendaftaran');
                
                $notifications->push([
                    'id' => 'sidang-' . $reg->id,
                    'title' => 'Pendaftaran Sidang',
                    'message' => ($reg->submission?->mahasiswa?->user?->name ?? 'Mahasiswa') . ' ' . $statusText . ' sidang.',
                    'icon' => 'gavel',
                    'url' => route('admin.skripsi.index', ['tab' => 'sidang']),
                    'human_time' => $safeHumanTime($when),
                    'created_at_ts' => Carbon::parse($when)->timestamp,
                    'needs_action' => in_array($status, ['submitted']),
                ]);
            }
        } catch (\Throwable $e) {}

        // 4) Pengajuan Jadwal
        try {
            $query = JadwalProposal::with(['dosen.user', 'mataKuliah'])->orderByDesc('updated_at');
            if (!$includeHandled) {
                $query->where('status', 'pending_admin');
            }
            $proposals = $query->limit($limit)->get();

            foreach ($proposals as $proposal) {
                $when = $proposal->updated_at ?? $proposal->created_at;
                $notifications->push([
                    'id' => 'jadwal-proposal-' . $proposal->id,
                    'title' => 'Persetujuan Jadwal',
                    'message' => 'Pengajuan jadwal dari ' . ($proposal->dosen?->user?->name ?? 'Dosen') . ' menunggu keputusan.',
                    'icon' => 'event_note',
                    'url' => route('admin.jadwal_admin_approval.index'),
                    'human_time' => $safeHumanTime($when),
                    'created_at_ts' => Carbon::parse($when)->timestamp,
                    'needs_action' => $proposal->status === 'pending_admin',
                ]);
            }
        } catch (\Throwable $e) {}

        // 5) Reschedule
        try {
            // Jadwal Reschedule
            $query = JadwalReschedule::with(['jadwal.kelas.mataKuliah', 'dosen'])->orderByDesc('updated_at');
            if (!$includeHandled) $query->where('status', 'pending');
            $jReschedules = $query->limit($limit)->get();

            foreach ($jReschedules as $res) {
                $when = $res->updated_at ?? $res->created_at;
                $notifications->push([
                    'id' => 'j-reschedule-' . $res->id,
                    'title' => 'Reschedule Jadwal',
                    'message' => ($res->dosen?->name ?? 'Dosen') . ' mengajukan reschedule.',
                    'icon' => 'edit_calendar',
                    'url' => route('admin.jadwal.reschedules'),
                    'human_time' => $safeHumanTime($when),
                    'created_at_ts' => Carbon::parse($when)->timestamp,
                    'needs_action' => $res->status === 'pending',
                ]);
            }

            // Kelas Reschedule
            $query = KelasReschedule::with(['kelasMataKuliah.mataKuliah', 'dosen.user'])->orderByDesc('updated_at');
            if (!$includeHandled) $query->where('status', 'pending');
            $kReschedules = $query->limit($limit)->get();

            foreach ($kReschedules as $res) {
                $when = $res->updated_at ?? $res->created_at;
                $notifications->push([
                    'id' => 'k-reschedule-' . $res->id,
                    'title' => 'Reschedule Kelas',
                    'message' => ($res->dosen?->user?->name ?? 'Dosen') . ' mengajukan reschedule kelas.',
                    'icon' => 'calendar_month',
                    'url' => route('admin.jadwal.index'),
                    'human_time' => $safeHumanTime($when),
                    'created_at_ts' => Carbon::parse($when)->timestamp,
                    'needs_action' => $res->status === 'pending',
                ]);
            }
        } catch (\Throwable $e) {}

        // 6) Magang
        try {
            $query = Internship::with('mahasiswa.user')->orderByDesc('updated_at');
            if (!$includeHandled) {
                $query->whereIn('status', [Internship::STATUS_SUBMITTED, Internship::STATUS_REQUEST_LETTER_UPLOADED, Internship::STATUS_UNDER_REVIEW]);
            }
            $internships = $query->limit($limit)->get();

            foreach ($internships as $internship) {
                $when = $internship->updated_at ?? $internship->created_at;
                $notifications->push([
                    'id' => 'magang-' . $internship->id,
                    'title' => 'Pengajuan Magang',
                    'message' => ($internship->mahasiswa?->user?->name ?? 'Mahasiswa') . ' sedang dalam tahap "' . $internship->status_label . '".',
                    'icon' => 'work_history',
                    'url' => route('admin.magang.show', $internship),
                    'human_time' => $safeHumanTime($when),
                    'created_at_ts' => Carbon::parse($when)->timestamp,
                    'needs_action' => in_array($internship->status, [Internship::STATUS_SUBMITTED, Internship::STATUS_REQUEST_LETTER_UPLOADED, Internship::STATUS_UNDER_REVIEW]),
                ]);
            }
        } catch (\Throwable $e) {}

        // 7) KRS Pending (Legacy)
        try {
            $query = Krs::with('mahasiswa.user')->where('status', 'pending')->orderByDesc('updated_at');
            $krsGrouped = $query->get()->groupBy('mahasiswa_id')->take(10);

            foreach ($krsGrouped as $mahasiswaId => $rows) {
                $latest = $rows->sortByDesc('updated_at')->first();
                $when = $latest?->updated_at ?? $latest?->created_at;
                $notifications->push([
                    'id' => 'krs-pending-' . $mahasiswaId,
                    'title' => 'KRS Menunggu Persetujuan',
                    'message' => ($latest?->mahasiswa?->user?->name ?? 'Mahasiswa') . ' memiliki ' . $rows->count() . ' KRS menunggu review.',
                    'icon' => 'assignment_turned_in',
                    'url' => route('admin.krs.index', ['status' => 'pending']),
                    'human_time' => $safeHumanTime($when),
                    'created_at_ts' => Carbon::parse($when)->timestamp,
                    'needs_action' => true,
                ]);
            }
        } catch (\Throwable $e) {}

        // 8) Availability Check
        try {
            $query = DosenAvailabilityCheck::with(['dosen.user', 'mataKuliah'])->orderByDesc('created_at');
            $checks = $query->limit($limit)->get();

            foreach ($checks as $check) {
                $when = $check->created_at;
                $notifications->push([
                    'id' => 'availability-' . $check->id,
                    'title' => 'Cek Ketersediaan',
                    'message' => ($check->dosen?->user?->name ?? 'Dosen') . ' meminta cek ketersediaan jadwal.',
                    'icon' => 'schedule_send',
                    'url' => route('admin.jadwal.index'),
                    'human_time' => $safeHumanTime($when),
                    'created_at_ts' => Carbon::parse($when)->timestamp,
                    'needs_action' => true,
                ]);
            }
        } catch (\Throwable $e) {}

        return $notifications->sortByDesc('created_at_ts')->values();
    }
}
