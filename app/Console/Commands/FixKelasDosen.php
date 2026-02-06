<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FixKelasDosen extends Command
{
    protected $signature = 'kelas:fix-dosen';
    protected $description = 'Fix kelas.dosen_id to use correct user_id from dosens table';

    public function handle()
    {
        $this->info('Fixing kelas.dosen_id...');
        
        // Get all kelas records
        $kelasRecords = DB::table('kelas')->get();
        
        $fixed = 0;
        $skipped = 0;
        
        foreach ($kelasRecords as $kelas) {
            // Check if current dosen_id is actually a users.id
            $userExists = DB::table('users')->where('id', $kelas->dosen_id)->exists();
            
            if ($userExists) {
                $this->line("Kelas ID {$kelas->id}: dosen_id {$kelas->dosen_id} already correct (is user_id)");
                $skipped++;
                continue;
            }
            
            // If not a user_id, assume it's a dosens.id and convert it
            $dosen = DB::table('dosens')->where('id', $kelas->dosen_id)->first();
            
            if ($dosen && $dosen->user_id) {
                DB::table('kelas')
                    ->where('id', $kelas->id)
                    ->update(['dosen_id' => $dosen->user_id]);
                
                $this->info("Kelas ID {$kelas->id}: Fixed dosen_id from {$kelas->dosen_id} (dosens.id) to {$dosen->user_id} (users.id)");
                $fixed++;
            } else {
                $this->warn("Kelas ID {$kelas->id}: dosen_id {$kelas->dosen_id} not found in dosens table");
                $skipped++;
            }
        }
        
        $this->newLine();
        $this->info("Done! Fixed: {$fixed}, Skipped: {$skipped}");
        
        return 0;
    }
}
