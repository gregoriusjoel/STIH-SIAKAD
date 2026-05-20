<?php

namespace Database\Seeders;

use App\Models\Prodi;
use App\Models\Fakultas;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProdiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if Fakultas Hukum exists or create it
        $fakultasHukum = Fakultas::where('nama_fakultas', 'Fakultas Hukum')->first();
        if (!$fakultasHukum) {
            $fakultasHukum = Fakultas::create([
                'kode_fakultas' => 'FH',
                'nama_fakultas' => 'Fakultas Hukum',
                'status' => 'aktif',
            ]);
        }

        $prodis = [
            [
                'kode_prodi' => 'INF',
                'nama_prodi' => 'Informatika',
                'jenjang' => 'S1',
                'status' => 'aktif',
            ],
            [
                'kode_prodi' => 'MAN',
                'nama_prodi' => 'Manajemen',
                'jenjang' => 'S1',
                'status' => 'aktif',
            ],
            [
                'kode_prodi' => 'AKT',
                'nama_prodi' => 'Akuntansi',
                'jenjang' => 'D3',
                'status' => 'aktif',
            ],
            [
                'kode_prodi' => 'HK',
                'nama_prodi' => 'Ilmu Hukum',
                'fakultas_id' => $fakultasHukum->id,
                'jenjang' => 'S1',
                'status' => 'aktif',
            ],
        ];

        foreach ($prodis as $prodi) {
            // Check if prodi already exists to avoid duplicate entry
            $existing = Prodi::where('kode_prodi', $prodi['kode_prodi'])->first();
            if (!$existing) {
                Prodi::create($prodi);
            }
        }
    }
}
