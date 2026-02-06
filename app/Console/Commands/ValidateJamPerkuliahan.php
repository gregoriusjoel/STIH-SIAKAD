<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\JamPerkuliahan;
use App\Models\MataKuliah;
use Carbon\Carbon;

class ValidateJamPerkuliahan extends Command
{
    protected $signature = 'jadwal:validate-jam';
    protected $description = 'Validate jam_perkuliahan slots for consecutive combinations (SKS requirements)';

    public function handle()
    {
        $this->info('Validating jam_perkuliahan for consecutive slot combinations...');
        $this->newLine();
        
        // Get all active jam perkuliahan
        $jamPerkuliahans = JamPerkuliahan::where('is_active', true)->orderBy('jam_ke')->get();
        
        if ($jamPerkuliahans->isEmpty()) {
            $this->warn('No active jam_perkuliahan found.');
            return 0;
        }
        
        $this->info("Found {$jamPerkuliahans->count()} active time slots (45 minutes each):");
        foreach ($jamPerkuliahans as $jam) {
            $this->line("  Jam ke-{$jam->jam_ke}: " . date('H:i', strtotime($jam->jam_mulai)) . " - " . date('H:i', strtotime($jam->jam_selesai)));
        }
        $this->newLine();
        
        // Check which SKS values exist in database
        $mataKuliahs = MataKuliah::select('sks')->distinct()->orderBy('sks')->get();
        
        $this->info('Checking consecutive slot availability for each SKS:');
        $this->newLine();
        
        foreach ($mataKuliahs as $mk) {
            $requiredSks = $mk->sks;
            $consecutiveSlots = $this->findConsecutiveSlots($jamPerkuliahans, $requiredSks);
            
            $count = count($consecutiveSlots);
            if ($count > 0) {
                $this->info("✓ {$requiredSks} SKS: {$count} consecutive combinations available");
                foreach ($consecutiveSlots as $slot) {
                    $this->line("    - Jam ke-{$slot['jam_ke_list']}: {$slot['jam_mulai']} - {$slot['jam_selesai']}");
                }
            } else {
                $this->warn("✗ {$requiredSks} SKS: No consecutive slots available (needs {$requiredSks} slots in a row)");
            }
            $this->newLine();
        }
        
        $this->info('Validation complete!');
        return 0;
    }
    
    private function findConsecutiveSlots($jamPerkuliahans, $requiredSks)
    {
        $combinations = [];
        $count = $jamPerkuliahans->count();
        
        if ($count < $requiredSks) {
            return $combinations;
        }
        
        $slots = $jamPerkuliahans->toArray();
        
        for ($i = 0; $i <= $count - $requiredSks; $i++) {
            $isConsecutive = true;
            $selectedSlots = [];
            
            for ($j = 0; $j < $requiredSks; $j++) {
                $currentSlot = $slots[$i + $j];
                $selectedSlots[] = $currentSlot;
                
                if ($j < $requiredSks - 1) {
                    $nextSlot = $slots[$i + $j + 1];
                    
                    $currentEnd = date('H:i', strtotime($currentSlot['jam_selesai']));
                    $nextStart = date('H:i', strtotime($nextSlot['jam_mulai']));
                    
                    if ($currentEnd !== $nextStart) {
                        $isConsecutive = false;
                        break;
                    }
                }
            }
            
            if ($isConsecutive && count($selectedSlots) === $requiredSks) {
                $firstSlot = $selectedSlots[0];
                $lastSlot = $selectedSlots[count($selectedSlots) - 1];
                
                $jamKeList = array_map(fn($s) => $s['jam_ke'], $selectedSlots);
                
                $combinations[] = [
                    'jam_mulai' => date('H:i', strtotime($firstSlot['jam_mulai'])),
                    'jam_selesai' => date('H:i', strtotime($lastSlot['jam_selesai'])),
                    'jam_ke' => $firstSlot['jam_ke'],
                    'jam_ke_list' => implode(',', $jamKeList),
                    'sks' => $requiredSks,
                ];
            }
        }
        
        return $combinations;
    }
}
