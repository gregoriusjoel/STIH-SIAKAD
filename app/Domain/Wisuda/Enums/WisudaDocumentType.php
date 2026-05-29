<?php

namespace App\Domain\Wisuda\Enums;

enum WisudaDocumentType: string
{
    case SURAT_PENYERAHAN_SKRIPSI = 'surat_penyerahan_skripsi';
    case PENYERAHAN_BUKU          = 'penyerahan_buku';
    case KETERANGAN_TURNITIN      = 'keterangan_turnitin';
    case SURAT_BEBAS_KEUANGAN     = 'surat_bebas_keuangan';
    case PAS_FOTO                 = 'pas_foto';

    public function label(): string
    {
        return match($this) {
            self::SURAT_PENYERAHAN_SKRIPSI => 'Surat Penyerahan Skripsi',
            self::PENYERAHAN_BUKU          => 'Bukti Penyerahan Buku',
            self::KETERANGAN_TURNITIN      => 'Keterangan Turnitin',
            self::SURAT_BEBAS_KEUANGAN     => 'Surat Bebas Keuangan',
            self::PAS_FOTO                 => 'Pas Foto',
        };
    }

    public function isRequired(): bool
    {
        // All document types are required
        return true;
    }

    public function acceptedMimes(): string
    {
        return match($this) {
            self::PAS_FOTO => 'jpg,jpeg,png,pdf',
            default        => 'pdf',
        };
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
