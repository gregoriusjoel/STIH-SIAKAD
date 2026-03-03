<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use Carbon\Carbon;

class Pertemuan extends Model
{
    /**
     * Valid meeting types (extensible for future: praktikum, quiz, etc.)
     */
    public const TIPE_KULIAH = 'kuliah';
    public const TIPE_UTS    = 'uts';
    public const TIPE_UAS    = 'uas';

    public const TIPE_LABELS = [
        self::TIPE_KULIAH => 'Kuliah',
        self::TIPE_UTS    => 'UTS',
        self::TIPE_UAS    => 'UAS',
    ];

    public const TIPE_ICONS = [
        self::TIPE_KULIAH => 'school',
        self::TIPE_UTS    => 'edit_note',
        self::TIPE_UAS    => 'assignment',
    ];

    public const TIPE_COLORS = [
        self::TIPE_KULIAH => 'bg-blue-50 text-blue-700 border-blue-200',
        self::TIPE_UTS    => 'bg-amber-50 text-amber-700 border-amber-200',
        self::TIPE_UAS    => 'bg-red-50 text-red-700 border-red-200',
    ];

    protected $fillable = [
        'kelas_mata_kuliah_id',
        'nomor_pertemuan',
        'tipe_pertemuan',
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
     * Get human-readable label for this meeting's type.
     */
    public function getTipeLabelAttribute(): string
    {
        return self::TIPE_LABELS[$this->tipe_pertemuan ?? self::TIPE_KULIAH] ?? ucfirst($this->tipe_pertemuan);
    }

    /**
     * Get display label: "Pertemuan 3" for kuliah, "UTS" for uts, "UAS" for uas.
     */
    public function getDisplayLabelAttribute(): string
    {
        return match ($this->tipe_pertemuan) {
            self::TIPE_UTS => 'UTS (Ujian Tengah Semester)',
            self::TIPE_UAS => 'UAS (Ujian Akhir Semester)',
            default        => 'Pertemuan ' . $this->nomor_pertemuan,
        };
    }

    /**
     * Get short display label for compact views.
     */
    public function getShortLabelAttribute(): string
    {
        return match ($this->tipe_pertemuan) {
            self::TIPE_UTS => 'UTS',
            self::TIPE_UAS => 'UAS',
            default        => 'P' . $this->nomor_pertemuan,
        };
    }

    /**
     * Check if this is an exam meeting (UTS/UAS).
     */
    public function isExam(): bool
    {
        return in_array($this->tipe_pertemuan, [self::TIPE_UTS, self::TIPE_UAS]);
    }

    /**
     * Scope: only regular kuliah meetings.
     */
    public function scopeKuliah($query)
    {
        return $query->where('tipe_pertemuan', self::TIPE_KULIAH);
    }

    /**
     * Scope: only exam meetings (UTS/UAS).
     */
    public function scopeExams($query)
    {
        return $query->whereIn('tipe_pertemuan', [self::TIPE_UTS, self::TIPE_UAS]);
    }

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
