<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TugasSubmission extends Model
{
    use HasFactory;

    protected $table = 'tugas_submissions';

    protected $fillable = [
        'tugas_id', 'mahasiswa_id', 'file_path', 'text_submission', 'comments', 'score', 'graded_by', 'graded_at'
    ];

    public function tugas()
    {
        return $this->belongsTo(Tugas::class, 'tugas_id');
    }

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'mahasiswa_id');
    }
}
