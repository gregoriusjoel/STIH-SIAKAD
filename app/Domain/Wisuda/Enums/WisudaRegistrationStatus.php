<?php

namespace App\Domain\Wisuda\Enums;

enum WisudaRegistrationStatus: string
{
    case PENDING   = 'pending';
    case APPROVED  = 'approved';
    case REJECTED  = 'rejected';
    case SCHEDULED = 'scheduled';

    public function label(): string
    {
        return match($this) {
            self::PENDING   => 'Menunggu Verifikasi',
            self::APPROVED  => 'Disetujui',
            self::REJECTED  => 'Ditolak',
            self::SCHEDULED => 'Terjadwal',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::PENDING   => 'yellow',
            self::APPROVED  => 'green',
            self::REJECTED  => 'red',
            self::SCHEDULED => 'indigo',
        };
    }

    /** Statuses that count as "active" — blocks re-registration. */
    public static function activeStatuses(): array
    {
        return [self::PENDING, self::APPROVED, self::SCHEDULED];
    }

    public static function allValues(): array
    {
        return array_column(self::cases(), 'value');
    }
}
