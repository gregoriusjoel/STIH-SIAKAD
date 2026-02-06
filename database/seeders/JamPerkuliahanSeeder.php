<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JamPerkuliahanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $timeSlots = [
            // Kelas Reguler (Jam 1-11)
            ['jam_ke' => 1, 'jam_mulai' => '09:00:00', 'jam_selesai' => '09:45:00', 'is_active' => true],
            ['jam_ke' => 2, 'jam_mulai' => '09:45:00', 'jam_selesai' => '10:30:00', 'is_active' => true],
            ['jam_ke' => 3, 'jam_mulai' => '10:30:00', 'jam_selesai' => '11:15:00', 'is_active' => true],
            ['jam_ke' => 4, 'jam_mulai' => '11:15:00', 'jam_selesai' => '12:00:00', 'is_active' => true],
            ['jam_ke' => 5, 'jam_mulai' => '13:00:00', 'jam_selesai' => '13:45:00', 'is_active' => true],
            ['jam_ke' => 6, 'jam_mulai' => '13:45:00', 'jam_selesai' => '14:30:00', 'is_active' => true],
            ['jam_ke' => 7, 'jam_mulai' => '14:30:00', 'jam_selesai' => '15:15:00', 'is_active' => true],
            ['jam_ke' => 8, 'jam_mulai' => '15:30:00', 'jam_selesai' => '16:15:00', 'is_active' => true],
            ['jam_ke' => 9, 'jam_mulai' => '16:15:00', 'jam_selesai' => '16:55:00', 'is_active' => true],
            ['jam_ke' => 10, 'jam_mulai' => '16:55:00', 'jam_selesai' => '17:45:00', 'is_active' => true],
            ['jam_ke' => 11, 'jam_mulai' => '17:45:00', 'jam_selesai' => '18:30:00', 'is_active' => true],
            
            // Kelas Reguler Khusus (Jam 12-16)
            ['jam_ke' => 12, 'jam_mulai' => '18:30:00', 'jam_selesai' => '19:15:00', 'is_active' => true],
            ['jam_ke' => 13, 'jam_mulai' => '19:15:00', 'jam_selesai' => '19:55:00', 'is_active' => true],
            ['jam_ke' => 14, 'jam_mulai' => '19:55:00', 'jam_selesai' => '20:40:00', 'is_active' => true],
            ['jam_ke' => 15, 'jam_mulai' => '20:40:00', 'jam_selesai' => '21:25:00', 'is_active' => true],
            ['jam_ke' => 16, 'jam_mulai' => '21:25:00', 'jam_selesai' => '22:10:00', 'is_active' => true],
        ];

        foreach ($timeSlots as $slot) {
            DB::table('jam_perkuliahan')->updateOrInsert(
                ['jam_ke' => $slot['jam_ke']],
                $slot
            );
        }
    }
}
