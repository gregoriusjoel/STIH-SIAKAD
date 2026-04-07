<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Storage;

/**
 * Model for the generic uploads table.
 * All files are stored on S3. Use UploadController or FileStorageService to create records.
 */
class Upload extends Model
{
    use HasFactory;

    protected $fillable = [
        'uploadable_type',
        'uploadable_id',
        'user_id',
        'file_path',
        'original_name',
        'mime_type',
        'extension',
        'folder',
        'size',
        'disk',
        'label',
    ];

    // ── Relationships ───────────────────────────────────────────────

    public function uploadable(): MorphTo
    {
        return $this->morphTo();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // ── Accessors ───────────────────────────────────────────────────

    /**
     * Returns the public S3 URL for this file.
     */
    public function getUrlAttribute(): string
    {
        return Storage::disk($this->disk ?? 's3')->url($this->file_path);
    }

    /**
     * Returns true if this file is an image (mime type starts with image/).
     */
    public function getIsImageAttribute(): bool
    {
        return str_starts_with($this->mime_type ?? '', 'image/');
    }

    /**
     * Human-readable file size.
     */
    public function getHumanSizeAttribute(): string
    {
        $bytes = $this->size ?? 0;

        if ($bytes < 1024) return $bytes . ' B';
        if ($bytes < 1048576) return round($bytes / 1024, 1) . ' KB';
        return round($bytes / 1048576, 1) . ' MB';
    }

    // ── Boot: auto-delete S3 file on model delete ───────────────────

    protected static function boot(): void
    {
        parent::boot();

        static::deleting(function (Upload $upload) {
            if ($upload->file_path) {
                Storage::disk($upload->disk ?? 's3')->delete($upload->file_path);
            }
        });
    }
}
