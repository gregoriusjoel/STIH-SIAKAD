<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\KelasMataKuliah;
use App\Models\Pertemuan;
use App\Models\Semester;
use Carbon\Carbon;

class InitPertemuanCommand extends Command
{
    protected $signature = 'pertemuan:init {--kelas_id= : ID kelas tertentu}';
    protected $description = 'Initialize pertemuan untuk semua kelas atau kelas tertentu';

    public function handle()
    {
        $this->info('🚀 Initializing pertemuans...');

        $kelasId = $this->option('kelas_id');
        
        $query = KelasMataKuliah::query();
        if ($kelasId) {
            $query->where('id', $kelasId);
        }
        
        $kelasList = $query->with('semester')->get();
        
        if ($kelasList->isEmpty()) {
            $this->warn('❌ No kelas found.');
            return 1;
        }

        $this->info("Found {$kelasList->count()} kelas to process.");
        
        $bar = $this->output->createProgressBar($kelasList->count());
        $bar->start();

        $totalCreated = 0;
        $totalSkipped = 0;

        foreach ($kelasList as $kelas) {
            $meetingCount = $kelas->meeting_count ?? 16;
            
            $semester = $kelas->semester ?? Semester::where('status', 'aktif')->first();
            $startDate = $semester && $semester->tanggal_mulai 
                ? Carbon::parse($semester->tanggal_mulai) 
                : Carbon::now();

            for ($i = 1; $i <= $meetingCount; $i++) {
                $existing = Pertemuan::where('kelas_mata_kuliah_id', $kelas->id)
                    ->where('nomor_pertemuan', $i)
                    ->first();

                if ($existing) {
                    $totalSkipped++;
                    continue;
                }

                Pertemuan::create([
                    'kelas_mata_kuliah_id' => $kelas->id,
                    'nomor_pertemuan' => $i,
                    'tanggal' => $startDate->copy()->addWeeks($i - 1),
                    'topik' => "Pertemuan $i",
                    'status' => 'scheduled',
                ]);

                $totalCreated++;
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        $this->info("✅ Done!");
        $this->table(
            ['Metric', 'Count'],
            [
                ['Created', $totalCreated],
                ['Skipped', $totalSkipped],
            ]
        );

        return 0;
    }
}
