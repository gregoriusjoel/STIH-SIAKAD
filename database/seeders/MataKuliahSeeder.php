<?php

namespace Database\Seeders;

use App\Models\MataKuliah;
use Illuminate\Database\Seeder;

class MataKuliahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $mataKuliahs = [
            ['kode' => 'IT-402', 'nama' => 'Pemrograman Web', 'sks' => 3, 'semester' => 4, 'jenis' => 'Praktikum'],
            ['kode' => 'IT-201', 'nama' => 'Struktur Data', 'sks' => 4, 'semester' => 2, 'jenis' => 'Teori'],
            ['kode' => 'IT-501', 'nama' => 'Kecerdasan Buatan', 'sks' => 3, 'semester' => 5, 'jenis' => 'Teori'],
            ['kode' => 'IT-405', 'nama' => 'Basis Data Lanjut', 'sks' => 3, 'semester' => 4, 'jenis' => 'Teori'],
            ['kode' => 'IT-303', 'nama' => 'Interaksi Manusia & Komputer', 'sks' => 3, 'semester' => 3, 'jenis' => 'Praktikum'],
            ['kode' => 'IT-101', 'nama' => 'Algoritma Pemrograman', 'sks' => 4, 'semester' => 1, 'jenis' => 'Praktikum'],
            ['kode' => 'IT-503', 'nama' => 'Machine Learning', 'sks' => 3, 'semester' => 5, 'jenis' => 'Teori'],
            ['kode' => 'IT-601', 'nama' => 'Proyek Akhir', 'sks' => 6, 'semester' => 6, 'jenis' => 'Praktikum'],
        ];

        foreach ($mataKuliahs as $mk) {
            MataKuliah::updateOrCreate(
                ['kode' => $mk['kode']],
                $mk
            );
        }
    }
}
