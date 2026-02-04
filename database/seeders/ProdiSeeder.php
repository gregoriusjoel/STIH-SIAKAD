<?php

namespace Database\Seeders;

use App\Models\Prodi;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProdiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
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
        ];

        foreach ($prodis as $prodi) {
            Prodi::create($prodi);
        }
    }
}
