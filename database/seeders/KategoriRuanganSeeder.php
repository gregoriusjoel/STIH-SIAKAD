<?php

namespace Database\Seeders;

use App\Models\KategoriRuangan;
use Illuminate\Database\Seeder;

class KategoriRuanganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kategoris = [
            [
                'nama_kategori' => 'Kelas',
                'deskripsi' => 'Ruangan untuk pembelajaran teori di kelas',
                'warna_badge' => 'blue',
                'urutan' => 1,
                'status' => 'aktif',
            ],
            [
                'nama_kategori' => 'Praktikum',
                'deskripsi' => 'Ruangan untuk praktikum dan latihan keterampilan',
                'warna_badge' => 'yellow',
                'urutan' => 2,
                'status' => 'aktif',
            ],
            [
                'nama_kategori' => 'Sidang',
                'deskripsi' => 'Ruangan untuk sidang dan ujian skripsi',
                'warna_badge' => 'purple',
                'urutan' => 3,
                'status' => 'aktif',
            ],
            [
                'nama_kategori' => 'Laboratorium',
                'deskripsi' => 'Ruangan untuk laboratorium dan penelitian',
                'warna_badge' => 'green',
                'urutan' => 4,
                'status' => 'aktif',
            ],
        ];

        foreach ($kategoris as $kategori) {
            KategoriRuangan::firstOrCreate(
                ['nama_kategori' => $kategori['nama_kategori']],
                $kategori
            );
        }
    }
}
