<?php

namespace App\Models;

class Kelas extends KelasMataKuliah
{
    // Legacy mapping: point Kelas to the merged kelas_mata_kuliahs table
    protected $table = 'kelas_mata_kuliahs';
}
