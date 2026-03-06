<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InternshipRevision extends Model
{
    protected $fillable = [
        'internship_id',
        'revision_no',
        'request_letter_signed_path',
        'note_from_admin',
        'note_from_mahasiswa',
    ];

    public function internship(): BelongsTo
    {
        return $this->belongsTo(Internship::class);
    }
}
