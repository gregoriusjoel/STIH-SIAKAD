<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InternshipType extends Model
{
    protected $fillable = [
        'code',
        'name',
        'description',
        'is_conversion',
        'max_conversion_sks',
        'is_active',
    ];

    protected $casts = [
        'is_conversion' => 'boolean',
        'max_conversion_sks' => 'integer',
        'is_active' => 'boolean',
    ];

    public function internships(): HasMany
    {
        return $this->hasMany(Internship::class);
    }
}
