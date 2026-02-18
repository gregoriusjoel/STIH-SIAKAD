<?php

namespace App\Models;

/**
 * Student Model - Alias untuk Mahasiswa
 * Untuk backward compatibility dengan sistem pembayaran
 */
class Student extends Mahasiswa
{
    // Inherit semua dari Mahasiswa
    // Model ini hanya sebagai alias untuk backward compatibility
}
