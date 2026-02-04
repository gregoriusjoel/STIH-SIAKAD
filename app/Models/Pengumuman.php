<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengumuman extends Model
{
    protected $table = 'pengumumans';

    protected $fillable = [
        'judul',
        'isi',
        'target',
        'published_at',
    ];

    protected $dates = ['published_at'];
}
