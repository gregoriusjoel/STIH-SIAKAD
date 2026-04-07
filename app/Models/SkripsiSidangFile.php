<?php

namespace App\Models;

use App\Domain\Skripsi\Enums\SidangFileType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class SkripsiSidangFile extends Model
{
    protected $table = 'skripsi_sidang_files';

    protected $fillable = [
        'sidang_registration_id',
        'file_type',
        'file_path',
        'original_name',
        'file_size',
    ];

    protected $casts = [
        'file_type' => SidangFileType::class,
    ];

    public function registration(): BelongsTo
    {
        return $this->belongsTo(SkripsiSidangRegistration::class, 'sidang_registration_id');
    }

    public function getUrlAttribute(): string
    {
        return Storage::url($this->file_path);
    }

    public function getFileSizeHumanAttribute(): string
    {
        $bytes = $this->file_size ?? 0;
        if ($bytes < 1024) {
            return $bytes . ' B';
        } elseif ($bytes < 1048576) {
            return round($bytes / 1024, 1) . ' KB';
        }
        return round($bytes / 1048576, 1) . ' MB';
    }
}
