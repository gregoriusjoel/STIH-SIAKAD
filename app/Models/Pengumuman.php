<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Traits\Auditable;

class Pengumuman extends Model
{
    use Auditable;

    protected $table = 'pengumumans';

    protected $fillable = [
        'judul',
        'isi',
        'target',
        'published_at',
    ];

    protected $dates = ['published_at'];
}
