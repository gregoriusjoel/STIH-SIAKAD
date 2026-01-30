<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $fillable = ['name','code','meta'];

    protected $casts = [
        'meta' => 'array',
    ];

    public function provinces()
    {
        return $this->hasMany(Province::class);
    }
}
