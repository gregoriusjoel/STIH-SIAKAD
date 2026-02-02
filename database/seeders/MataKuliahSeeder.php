<?php

namespace Database\Seeders;

use App\Models\MataKuliah;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MataKuliahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $csvFile = base_path('master/matkul_stih.csv');
        
        if (!file_exists($csvFile)) {
            $this->command->error('File matkul_stih.csv tidak ditemukan!');
            return;
        }

        // Clear existing data
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        MataKuliah::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $file = fopen($csvFile, 'r');
        
        // Skip header
        fgetcsv($file, 1000, ';');
        
        $imported = 0;
        while (($data = fgetcsv($file, 1000, ';')) !== false) {
            if (count($data) >= 6) {
                try {
                    $kodeId = trim($data[0]);
                    $kodeMK = trim($data[1]);
                    $nama = trim($data[2]);
                    $praktikum = trim($data[3]);
                    $sks = (int) (isset($data[4]) ? preg_replace('/[^0-9]/', '', $data[4]) : 0);
                    $semester = (int) (isset($data[5]) ? preg_replace('/[^0-9]/', '', $data[5]) : 0);

                    // Determine jenis based on code prefix
                    $jenis = 'wajib_prodi'; // default
                    $kategori = 'Mata Kuliah Wajib Prodi';
                    if (preg_match('/^ADH1/', $kodeMK)) {
                        $jenis = 'wajib_nasional';
                        $kategori = 'Mata Kuliah Wajib Nasional';
                    } elseif (preg_match('/^ADH2/', $kodeMK)) {
                        $jenis = 'wajib_prodi';
                        $kategori = 'Mata Kuliah Wajib Prodi';
                    } elseif (preg_match('/^ADH3/', $kodeMK)) {
                        $jenis = 'pilihan';
                        $kategori = 'Mata Kuliah Pilihan';
                    } elseif (preg_match('/^ADH4/', $kodeMK)) {
                        $jenis = 'peminatan';
                        $kategori = 'Mata Kuliah Peminatan';
                    }

                    $deskripsi = $kategori;
                    if (!empty($praktikum)) {
                        $deskripsi .= ' • Praktikum: ' . $praktikum;
                    }

                    MataKuliah::create([
                        'kode_id' => $kodeId,
                        'kode_mk' => $kodeMK,
                        'praktikum' => is_numeric($praktikum) ? (int)$praktikum : null,
                        'nama_mk' => $nama,
                        'sks' => $sks,
                        'semester' => $semester,
                        'jenis' => $jenis,
                        'prodi' => 'Ilmu Hukum',
                        'deskripsi' => $deskripsi,
                    ]);
                    $imported++;
                } catch (\Exception $e) {
                    $this->command->warn("Gagal import: {$data[1]} - " . $e->getMessage());
                }
            }
        }
        
        fclose($file);
        
        $this->command->info("Berhasil import {$imported} mata kuliah dari CSV!");
    }
}
