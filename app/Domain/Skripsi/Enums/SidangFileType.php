<?php

namespace App\Domain\Skripsi\Enums;

enum SidangFileType: string
{
    case FORM_SIDANG    = 'form_sidang';
    case BEBAS_PUSTAKA  = 'bebas_pustaka';
    case TRANSKRIP      = 'transkrip';
    case FORM_BIMBINGAN = 'form_bimbingan';
    case FILE_SKRIPSI   = 'file_skripsi';
    case FILE_PPT       = 'file_ppt';
    case LAINNYA        = 'lainnya';

    public function label(): string
    {
        return match($this) {
            self::FORM_SIDANG    => 'Formulir Pendaftaran Sidang',
            self::BEBAS_PUSTAKA  => 'Surat Bebas Pustaka',
            self::TRANSKRIP      => 'Transkrip Nilai',
            self::FORM_BIMBINGAN => 'Log Bimbingan',
            self::FILE_SKRIPSI   => 'File Skripsi (PDF)',
            self::FILE_PPT       => 'File Presentasi (PPT)',
            self::LAINNYA        => 'Dokumen Lainnya',
        };
    }

    public function isRequired(): bool
    {
        return in_array($this, [
            self::FILE_SKRIPSI,
            self::FILE_PPT,
            self::FORM_SIDANG,
            self::TRANSKRIP,
        ], true);
    }

    /** Files that dosen penguji/pembimbing can access after scheduling. */
    public function isSharedWithDosen(): bool
    {
        return in_array($this, [self::FILE_SKRIPSI, self::FILE_PPT], true);
    }

    public static function required(): array
    {
        return array_filter(self::cases(), fn($c) => $c->isRequired());
    }

    public static function allValues(): array
    {
        return array_column(self::cases(), 'value');
    }
}
