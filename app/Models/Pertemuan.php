<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use Carbon\Carbon;

class Pertemuan extends Model
{
    protected $fillable = [
        'kelas_mata_kuliah_id',
        'nomor_pertemuan',
        'tanggal',
        'topik',
        'deskripsi',
        'metode_pengajaran',
        'qr_token',
        'qr_enabled',
        'qr_expires_at',
        'qr_generated_at',
        'status',
        'online_meeting_link',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'qr_enabled' => 'boolean',
        'qr_expires_at' => 'datetime',
        'qr_generated_at' => 'datetime',
        'metode_pengajaran' => 'string',
    ];

    /**
     * Dosen attendance record for this pertemuan.
     */
    public function dosenAttendance(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(DosenAttendance::class);
    }

    /**
     * Relasi ke kelas mata kuliah
     */
    public function kelasMataKuliah(): BelongsTo
    {
        return $this->belongsTo(KelasMataKuliah::class);
    }

    /**
     * Relasi ke presensi mahasiswa
     */
    public function presensis(): HasMany
    {
        return $this->hasMany(Presensi::class, 'pertemuan', 'nomor_pertemuan')
            ->where('kelas_mata_kuliah_id', $this->kelas_mata_kuliah_id);
    }

    /**
     * Generate QR token unik untuk pertemuan ini
     */
    public function generateQrToken(int $expiryMinutes = 5): void
    {
        $this->qr_token = Str::random(64);
        $this->qr_generated_at = now();
        $this->qr_expires_at = now()->addMinutes($expiryMinutes);
        $this->qr_enabled = true;
        $this->save();
    }

    /**
     * Activate QR dengan expiry time
     */
    public function activateQr(int $expiryMinutes = 5): void
    {
        if (empty($this->qr_token)) {
            $this->qr_token = Str::random(64);
        }
        $this->qr_enabled = true;
        $this->qr_expires_at = now()->addMinutes($expiryMinutes);
        $this->save();
    }

    /**
     * Disable QR code
     */
    public function disableQr(): void
    {
        $this->qr_enabled = false;
        $this->save();
    }

    /**
     * Check apakah QR masih valid (aktif dan belum expired)
     */
    public function isQrValid(): bool
    {
        if (!$this->qr_enabled || !$this->qr_token) {
            return false;
        }

        if ($this->qr_expires_at && Carbon::now()->gt($this->qr_expires_at)) {
            // Auto-disable expired QR
            $this->disableQr();
            return false;
        }

        return true;
    }

    /**
     * Get jumlah mahasiswa yang sudah absen
     */
    public function getJumlahAbsenAttribute(): int
    {
        return $this->presensis()->where('status', 'hadir')->count();
    }
}
