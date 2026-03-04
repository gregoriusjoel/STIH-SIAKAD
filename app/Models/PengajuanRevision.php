<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengajuanRevision extends Model
{
    protected $fillable = [
        'pengajuan_id',
        'revision_no',
        'signed_doc_path',
        'note_from_admin',
        'note_from_mahasiswa',
    ];

    public function pengajuan()
    {
        return $this->belongsTo(Pengajuan::class);
    }
}
