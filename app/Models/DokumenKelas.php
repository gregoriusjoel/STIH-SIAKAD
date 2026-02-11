<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DokumenKelas extends Model
{
    protected $table = 'dokumen_kelas';

    protected $fillable = [
        'kelas_id',
        'tipe_dokumen',
        'nama_file',
        'path_file',
        'uploaded_by',
    ];

    /**
     * Get the kelas that owns the document
     */
    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    /**
     * Get the user who uploaded the document
     */
    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    /**
     * Get the full URL for the document
     */
    public function getUrlAttribute(): string
    {
        return asset('storage/' . $this->path_file);
    }
}
