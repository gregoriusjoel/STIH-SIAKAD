<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Ruangan;

class RuanganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ruangans = [
            // Gedung A - Lantai 1
            ['kode_ruangan' => 'R.101', 'nama_ruangan' => 'Ruang Kelas A1', 'gedung' => 'Gedung A', 'lantai' => 1, 'kapasitas' => 40, 'status' => 'aktif'],
            ['kode_ruangan' => 'R.102', 'nama_ruangan' => 'Ruang Kelas A2', 'gedung' => 'Gedung A', 'lantai' => 1, 'kapasitas' => 35, 'status' => 'aktif'],
            ['kode_ruangan' => 'R.103', 'nama_ruangan' => 'Ruang Kelas A3', 'gedung' => 'Gedung A', 'lantai' => 1, 'kapasitas' => 30, 'status' => 'aktif'],
            ['kode_ruangan' => 'R.104', 'nama_ruangan' => 'Ruang Kelas A4', 'gedung' => 'Gedung A', 'lantai' => 1, 'kapasitas' => 45, 'status' => 'aktif'],
            ['kode_ruangan' => 'R.105', 'nama_ruangan' => 'Ruang Kelas A5', 'gedung' => 'Gedung A', 'lantai' => 1, 'kapasitas' => 40, 'status' => 'aktif'],

            // Gedung A - Lantai 2
            ['kode_ruangan' => 'R.201', 'nama_ruangan' => 'Ruang Kelas B1', 'gedung' => 'Gedung A', 'lantai' => 2, 'kapasitas' => 50, 'status' => 'aktif'],
            ['kode_ruangan' => 'R.202', 'nama_ruangan' => 'Ruang Kelas B2', 'gedung' => 'Gedung A', 'lantai' => 2, 'kapasitas' => 45, 'status' => 'aktif'],
            ['kode_ruangan' => 'R.203', 'nama_ruangan' => 'Ruang Kelas B3', 'gedung' => 'Gedung A', 'lantai' => 2, 'kapasitas' => 40, 'status' => 'aktif'],

            // Lab Komputer
            ['kode_ruangan' => 'LAB.01', 'nama_ruangan' => 'Lab Komputer 1', 'gedung' => 'Gedung B', 'lantai' => 1, 'kapasitas' => 30, 'status' => 'aktif'],
            ['kode_ruangan' => 'LAB.02', 'nama_ruangan' => 'Lab Komputer 2', 'gedung' => 'Gedung B', 'lantai' => 1, 'kapasitas' => 25, 'status' => 'aktif'],

            // Ruang Praktikum
            ['kode_ruangan' => 'PRAK.01', 'nama_ruangan' => 'Ruang Praktikum Hukum 1', 'gedung' => 'Gedung B', 'lantai' => 2, 'kapasitas' => 35, 'status' => 'aktif'],
            ['kode_ruangan' => 'PRAK.02', 'nama_ruangan' => 'Ruang Praktikum Hukum 2', 'gedung' => 'Gedung B', 'lantai' => 2, 'kapasitas' => 30, 'status' => 'aktif'],

            // Aula & Ruang Besar
            ['kode_ruangan' => 'AULA.01', 'nama_ruangan' => 'Aula Utama', 'gedung' => 'Gedung C', 'lantai' => 1, 'kapasitas' => 200, 'status' => 'aktif'],
            ['kode_ruangan' => 'SEMINAR.01', 'nama_ruangan' => 'Ruang Seminar', 'gedung' => 'Gedung C', 'lantai' => 1, 'kapasitas' => 80, 'status' => 'aktif'],
        ];

        foreach ($ruangans as $ruangan) {
            Ruangan::create($ruangan);
        }
    }
}
