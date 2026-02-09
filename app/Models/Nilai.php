<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Nilai extends Model
{
    protected $table = 'nilai';
    
    protected $fillable = [
        'krs_id',
        'kelas_id',
        'nilai_partisipatif',
        'nilai_proyek',
        'nilai_quiz',
        'nilai_tugas',
        'nilai_uts',
        'nilai_uas',
        'nilai_akhir',
        'grade',
        'bobot',
        'is_published',
        'published_at',
        'published_by',
    ];

    protected $casts = [
        'nilai_partisipatif' => 'decimal:2',
        'nilai_proyek' => 'decimal:2',
        'nilai_quiz' => 'decimal:2',
        'nilai_tugas' => 'decimal:2',
        'nilai_uts' => 'decimal:2',
        'nilai_uas' => 'decimal:2',
        'nilai_akhir' => 'decimal:2',
        'bobot' => 'decimal:2',
        'is_published' => 'boolean',
        'published_at' => 'datetime',
    ];

    public function krs(): BelongsTo
    {
        return $this->belongsTo(Krs::class);
    }

    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class);
    }

    public function bobotPenilaian(): BelongsTo
    {
        return $this->belongsTo(BobotPenilaian::class, 'kelas_id', 'kelas_id');
    }

    public function publishedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'published_by');
    }

    /**
     * Scope to filter only published grades
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    /**
     * Calculate final grade based on bobot penilaian
     */
    public function calculateNilaiAkhir(BobotPenilaian $bobot): float
    {
        $nilai = 0;
        
        $nilai += ($this->nilai_partisipatif ?? 0) * ($bobot->bobot_partisipatif / 100);
        $nilai += ($this->nilai_proyek ?? 0) * ($bobot->bobot_proyek / 100);
        $nilai += ($this->nilai_quiz ?? 0) * ($bobot->bobot_quiz / 100);
        $nilai += ($this->nilai_tugas ?? 0) * ($bobot->bobot_tugas / 100);
        $nilai += ($this->nilai_uts ?? 0) * ($bobot->bobot_uts / 100);
        $nilai += ($this->nilai_uas ?? 0) * ($bobot->bobot_uas / 100);
        
        return round($nilai, 2);
    }

    /**
     * Convert nilai to grade based on STIH Adhyaksa standard
     */
    public static function convertToGrade(float $nilai): array
    {
        if ($nilai >= 80) {
            return ['grade' => 'A', 'bobot' => 4.00];
        } elseif ($nilai >= 76) {
            return ['grade' => 'A-', 'bobot' => 3.67];
        } elseif ($nilai >= 72) {
            return ['grade' => 'B+', 'bobot' => 3.33];
        } elseif ($nilai >= 68) {
            return ['grade' => 'B', 'bobot' => 3.00];
        } elseif ($nilai >= 64) {
            return ['grade' => 'B-', 'bobot' => 2.67];
        } elseif ($nilai >= 60) {
            return ['grade' => 'C+', 'bobot' => 2.33];
        } elseif ($nilai >= 56) {
            return ['grade' => 'C', 'bobot' => 2.00];
        } elseif ($nilai >= 45) {
            return ['grade' => 'D', 'bobot' => 1.00];
        } else {
            return ['grade' => 'E', 'bobot' => 0.00];
        }
    }

    /**
     * Auto calculate and set grade/bobot
     */
    public function autoCalculateGrade(BobotPenilaian $bobot): void
    {
        $nilaiAkhir = $this->calculateNilaiAkhir($bobot);
        $gradeData = self::convertToGrade($nilaiAkhir);
        
        $this->nilai_akhir = $nilaiAkhir;
        $this->grade = $gradeData['grade'];
        $this->bobot = $gradeData['bobot'];
    }
}

