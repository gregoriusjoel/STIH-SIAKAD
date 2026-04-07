<?php

namespace App\Domain\Skripsi\Enums;

enum GuidanceStatus: string
{
    case PENDING  = 'pending';
    case REVIEWED = 'reviewed';
    case APPROVED = 'approved';
    case REJECTED = 'rejected'; // dosen minta perbaikan

    public function label(): string
    {
        return match($this) {
            self::PENDING  => 'Menunggu Review',
            self::REVIEWED => 'Sudah Direview',
            self::APPROVED => 'Disetujui',
            self::REJECTED => 'Perlu Revisi',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::PENDING  => 'yellow',
            self::REVIEWED => 'blue',
            self::APPROVED => 'green',
            self::REJECTED => 'red',
        };
    }
}
