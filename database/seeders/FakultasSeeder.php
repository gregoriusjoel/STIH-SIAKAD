<?php

namespace Database\Seeders;

use App\Models\Fakultas;
use App\Models\Prodi;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FakultasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $prodiInf = Prodi::where('kode_prodi', 'INF')->first();
        $prodiMan = Prodi::where('kode_prodi', 'MAN')->first();
        $prodiAkt = Prodi::where('kode_prodi', 'AKT')->first();

        if ($prodiInf) {
            Fakultas::create([
                'kode_fakultas' => 'FTEK',
                'nama_fakultas' => 'Fakultas Teknik',
                'prodi_id' => $prodiInf->id,
                'status' => 'aktif',
            ]);
        }

        if ($prodiMan) {
            Fakultas::create([
                'kode_fakultas' => 'FEB',
                'nama_fakultas' => 'Fakultas Ekonomi dan Bisnis',
                'prodi_id' => $prodiMan->id,
                'status' => 'aktif',
            ]);
        }

        if ($prodiAkt) {
            Fakultas::create([
                'kode_fakultas' => 'FEKON',
                'nama_fakultas' => 'Fakultas Ekonomi',
                'prodi_id' => $prodiAkt->id,
                'status' => 'aktif',
            ]);
        }
    }
}
