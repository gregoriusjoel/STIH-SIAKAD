<?php

namespace Database\Seeders;

use App\Models\Jadwal;
use App\Models\Kelas;
use Illuminate\Database\Seeder;

class JadwalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kelasList = Kelas::with('mataKuliah')->get();
        
        if ($kelasList->isEmpty()) {
            $this->command->warn('No kelas found. Skipping JadwalSeeder.');
            return;
        }

        $schedules = [
            ['hari' => 'Senin', 'jam_mulai' => '08:00', 'jam_selesai' => '10:30', 'ruangan' => 'Lab Komputer 1'],
            ['hari' => 'Senin', 'jam_mulai' => '13:00', 'jam_selesai' => '15:30', 'ruangan' => 'Lab Komputer 1'],
            ['hari' => 'Selasa', 'jam_mulai' => '08:00', 'jam_selesai' => '10:30', 'ruangan' => 'R. Teori 302'],
            ['hari' => 'Selasa', 'jam_mulai' => '13:00', 'jam_selesai' => '15:30', 'ruangan' => 'R. Teori 302'],
            ['hari' => 'Rabu', 'jam_mulai' => '10:30', 'jam_selesai' => '13:00', 'ruangan' => 'R. Multimedia'],
            ['hari' => 'Kamis', 'jam_mulai' => '09:00', 'jam_selesai' => '11:30', 'ruangan' => 'R. Teori 305'],
            ['hari' => 'Jumat', 'jam_mulai' => '08:00', 'jam_selesai' => '10:30', 'ruangan' => 'Lab Komputer 2'],
            ['hari' => 'Jumat', 'jam_mulai' => '13:00', 'jam_selesai' => '16:00', 'ruangan' => 'Lab Komputer 3'],
        ];

        foreach ($kelasList as $index => $kelas) {
            if ($index >= count($schedules)) break;

            $schedule = $schedules[$index];
            
            Jadwal::updateOrCreate(
                [
                    'kelas_id' => $kelas->id,
                    'hari' => $schedule['hari'],
                ],
                [
                    'jam_mulai' => $schedule['jam_mulai'],
                    'jam_selesai' => $schedule['jam_selesai'],
                    'ruangan' => $schedule['ruangan'],
                    'status' => 'active', // Already approved and assigned room
                    'catatan_dosen' => null,
                    'catatan_admin' => null,
                ]
            );
        }
    }
}
