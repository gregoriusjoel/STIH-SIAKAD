<?php

namespace App\Domain\Skripsi\Enums;

enum SkripsiStatus: string
{
    // Belum memenuhi syarat SKS
    case LOCKED              = 'LOCKED';

    // Pengajuan Proposal
    case PROPOSAL_DRAFT      = 'PROPOSAL_DRAFT';
    case PROPOSAL_PENDING_SUPERVISOR = 'PROPOSAL_PENDING_SUPERVISOR';
    case PROPOSAL_SUBMITTED  = 'PROPOSAL_SUBMITTED';
    case PROPOSAL_REJECTED   = 'PROPOSAL_REJECTED';
    case PROPOSAL_APPROVED   = 'PROPOSAL_APPROVED';

    // Bimbingan
    case BIMBINGAN_ACTIVE    = 'BIMBINGAN_ACTIVE';
    case ELIGIBLE_SIDANG     = 'ELIGIBLE_SIDANG'; // bimbingan >= 8

    // Pendaftaran Sidang
    case SIDANG_REG_DRAFT    = 'SIDANG_REG_DRAFT';
    case SIDANG_REG_SUBMITTED = 'SIDANG_REG_SUBMITTED';
    case SIDANG_REG_REJECTED = 'SIDANG_REG_REJECTED';

    // Sidang
    case SIDANG_SCHEDULED    = 'SIDANG_SCHEDULED';
    case SIDANG_COMPLETED    = 'SIDANG_COMPLETED';

    // Revisi
    case REVISION_PENDING    = 'REVISION_PENDING';
    case REVISION_UPLOADED   = 'REVISION_UPLOADED';
    case REVISION_APPROVED   = 'REVISION_APPROVED';

    // Final — DB value tetap 'THESIS_COMPLETED' untuk backward compatibility
    case SKRIPSI_COMPLETED   = 'THESIS_COMPLETED';

    public function label(): string
    {
        return match($this) {
            self::LOCKED              => 'Belum Memenuhi Syarat SKS',
            self::PROPOSAL_DRAFT      => 'Draft Proposal',
            self::PROPOSAL_PENDING_SUPERVISOR => 'Menunggu Konfirmasi Dosen',
            self::PROPOSAL_SUBMITTED  => 'Menunggu Review Admin',
            self::PROPOSAL_REJECTED   => 'Proposal Ditolak',
            self::PROPOSAL_APPROVED   => 'Proposal Disetujui',
            self::BIMBINGAN_ACTIVE    => 'Bimbingan Aktif',
            self::ELIGIBLE_SIDANG     => 'Siap Daftar Sidang',
            self::SIDANG_REG_DRAFT    => 'Draft Pendaftaran Sidang',
            self::SIDANG_REG_SUBMITTED => 'Pendaftaran Sidang Dikirim',
            self::SIDANG_REG_REJECTED => 'Pendaftaran Sidang Ditolak',
            self::SIDANG_SCHEDULED    => 'Sidang Dijadwalkan',
            self::SIDANG_COMPLETED    => 'Sidang Selesai',
            self::REVISION_PENDING    => 'Menunggu Upload Revisi',
            self::REVISION_UPLOADED   => 'Revisi Dikirim',
            self::REVISION_APPROVED   => 'Revisi Disetujui',
            self::SKRIPSI_COMPLETED   => 'Skripsi Selesai',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::LOCKED              => 'gray',
            self::PROPOSAL_DRAFT      => 'gray',
            self::PROPOSAL_PENDING_SUPERVISOR => 'purple',
            self::PROPOSAL_SUBMITTED  => 'yellow',
            self::PROPOSAL_REJECTED   => 'red',
            self::PROPOSAL_APPROVED   => 'green',
            self::BIMBINGAN_ACTIVE    => 'blue',
            self::ELIGIBLE_SIDANG     => 'teal',
            self::SIDANG_REG_DRAFT    => 'gray',
            self::SIDANG_REG_SUBMITTED => 'yellow',
            self::SIDANG_REG_REJECTED => 'red',
            self::SIDANG_SCHEDULED    => 'indigo',
            self::SIDANG_COMPLETED    => 'purple',
            self::REVISION_PENDING    => 'orange',
            self::REVISION_UPLOADED   => 'yellow',
            self::REVISION_APPROVED   => 'green',
            self::SKRIPSI_COMPLETED   => 'emerald',
        };
    }

    /** Returns allowed next statuses from this state (for validation / admin actions). */
    public function allowedTransitions(): array
    {
        return match($this) {
            self::LOCKED              => [self::PROPOSAL_DRAFT],
            self::PROPOSAL_DRAFT      => [self::PROPOSAL_PENDING_SUPERVISOR],
            self::PROPOSAL_PENDING_SUPERVISOR => [self::PROPOSAL_SUBMITTED, self::PROPOSAL_DRAFT],
            self::PROPOSAL_SUBMITTED  => [self::PROPOSAL_APPROVED, self::PROPOSAL_REJECTED],
            self::PROPOSAL_REJECTED   => [self::PROPOSAL_DRAFT],
            self::PROPOSAL_APPROVED   => [self::BIMBINGAN_ACTIVE],
            self::BIMBINGAN_ACTIVE    => [self::ELIGIBLE_SIDANG],
            self::ELIGIBLE_SIDANG     => [self::SIDANG_REG_DRAFT],
            self::SIDANG_REG_DRAFT    => [self::SIDANG_REG_SUBMITTED],
            self::SIDANG_REG_SUBMITTED => [self::SIDANG_SCHEDULED, self::SIDANG_REG_REJECTED],
            self::SIDANG_REG_REJECTED => [self::SIDANG_REG_DRAFT],
            self::SIDANG_SCHEDULED    => [self::SIDANG_COMPLETED],
            self::SIDANG_COMPLETED    => [self::REVISION_PENDING],
            self::REVISION_PENDING    => [self::REVISION_UPLOADED],
            self::REVISION_UPLOADED   => [self::REVISION_APPROVED],
            self::REVISION_APPROVED   => [self::SKRIPSI_COMPLETED],
            self::SKRIPSI_COMPLETED   => [],
        };
    }

    public function canTransitionTo(self $target): bool
    {
        return in_array($target, $this->allowedTransitions(), true);
    }

    /** Step number for the progress tracker UI (1-8). Null = not yet started. */
    public function step(): int
    {
        return match($this) {
            self::LOCKED              => 1,
            self::PROPOSAL_DRAFT,
            self::PROPOSAL_PENDING_SUPERVISOR,
            self::PROPOSAL_REJECTED   => 2,
            self::PROPOSAL_SUBMITTED  => 2,
            self::PROPOSAL_APPROVED,
            self::BIMBINGAN_ACTIVE,
            self::ELIGIBLE_SIDANG     => 3,
            self::SIDANG_REG_DRAFT,
            self::SIDANG_REG_SUBMITTED,
            self::SIDANG_REG_REJECTED => 4,
            self::SIDANG_SCHEDULED    => 5,
            self::SIDANG_COMPLETED,
            self::REVISION_PENDING,
            self::REVISION_UPLOADED   => 6,
            self::REVISION_APPROVED,
            self::SKRIPSI_COMPLETED   => 7,
        };
    }
}
