<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kelas extends Model
{
    use HasFactory;

    protected $table = 'kelas';

    protected $fillable = [
        'mata_kuliah_id',
        'dosen_id',
        'kapasitas',
        'tahun_ajaran',
        'semester_type',
        'kelas_perkuliahan_id',
    ];

    /**
     * Get the course for this class
     */
    public function mataKuliah(): BelongsTo
    {
        return $this->belongsTo(MataKuliah::class);
    }

    /**
     * Get the dosen (lecturer) for this class
     */
    public function dosen(): BelongsTo
    {
        return $this->belongsTo(Dosen::class, 'dosen_id');
    }

    /**
     * Get all schedules for this class
     */
    public function jadwals(): HasMany
    {
        return $this->hasMany(Jadwal::class);
    }
    public function krs(): HasMany
    {
        return $this->hasMany(Krs::class);
    }

    public function bobotPenilaian()
    {
        return $this->hasOne(BobotPenilaian::class);
    }

    public function mahasiswas()
    {
        // KRS status is standardized to 'sudah submit' for all submitted/approved entries.
        return $this->belongsToMany(Mahasiswa::class, 'krs', 'kelas_id', 'mahasiswa_id')
            ->withPivot('status')
            ->wherePivot('status', 'sudah submit');
    }

    /**
     * Get all documents for this class
     */
    public function dokumen(): HasMany
    {
        return $this->hasMany(DokumenKelas::class, 'kelas_id');
    }

    /**
     * Get silabus document for this class
     */
    public function silabus()
    {
        return $this->hasOne(DokumenKelas::class, 'kelas_id')->where('tipe_dokumen', 'silabus');
    }

    /**
     * Get RPS document for this class
     */
    public function rps()
    {
        return $this->hasOne(DokumenKelas::class, 'kelas_id')->where('tipe_dokumen', 'rps');
    }

    /**
     * Get the class name (nama_kelas) from KelasPerkuliahan relationship
     * Accessible as $kelas->resolved_kelas_name
     */
    public function getResolvedKelasNameAttribute(): string
    {
        // Must have kelas_perkuliahan_id to function
        if (!$this->kelas_perkuliahan_id) {
            return '-';
        }
        
        return $this->kelasPerkuliahan?->nama_kelas ?? $this->kelasPerkuliahan?->kode_kelas ?? '-';
    }

    /**
     * Get the Kelas Perkuliahan master data (primary relation)
     */
    public function kelasPerkuliahan(): BelongsTo
    {
        return $this->belongsTo(KelasPerkuliahan::class);
    }
}
