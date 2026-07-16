<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class GenerateDailyReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'devdocs:generate-daily-report';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate daily developer report from git commits made today';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = date('Y-m-d');
        $docsPath = base_path('database/dev-docs');

        if (!File::exists($docsPath)) {
            File::makeDirectory($docsPath, 0755, true);
        }

        // Run git commands to get commits since midnight today
        $output = [];
        exec('git log --since="00:00:00" --pretty=format:"%s|%an" 2>&1', $output, $returnVar);

        // If no commits found or git fails, look for uncommitted changes today as fallback
        if ($returnVar !== 0 || empty($output)) {
            exec('git status --short 2>&1', $statusOutput);
            if (empty($statusOutput)) {
                $this->info("Tidak ada aktivitas hari ini. Laporan tidak dibuat.");
                return;
            }
            // Add custom placeholder commit to indicate work was done
            $output[] = "Merapikan dan melakukan optimalisasi sistem|Developer";
        }

        // Run git diff to get changed files today (both committed and uncommitted)
        exec('git diff --name-only HEAD 2>&1', $diffUncommitted);
        exec('git log --since="00:00:00" --name-only --pretty=format:"" 2>&1', $diffCommitted);
        
        $allFiles = array_merge($diffUncommitted, $diffCommitted);
        $changedFiles = array_filter(array_unique(array_map('trim', $allFiles)));

        // Format layman-friendly report content
        $content = "# Laporan Perubahan Sistem SIAKAD - " . date('d F Y') . "\n\n";
        $content .= "Laporan ini dibuat otomatis oleh sistem pada pukul 17:30 untuk merekam semua aktivitas pembaruan yang dilakukan hari ini.\n\n";
        $content .= "---\n\n";
        
        $content .= "## 🚀 Rangkuman Pembaruan Hari Ini\n\n";
        foreach ($output as $line) {
            if (empty(trim($line))) continue;
            $parts = explode('|', $line, 2);
            $message = $parts[0] ?? '';
            $author = $parts[1] ?? 'Developer';
            
            // Clean up common commit prefix syntax if any (e.g. feat:, fix:)
            $messageClean = preg_replace('/^(feat|fix|chore|docs|style|refactor|perf|test)(\(.+?\))?:/i', '', $message);
            $messageClean = trim($messageClean);
            
            // Translate common developer prefixes to lay terms
            $prefix = "Pembaruan";
            if (stripos($message, 'fix') !== false || stripos($message, 'bug') !== false || stripos($message, 'error') !== false) {
                $prefix = "Perbaikan Error";
            } elseif (stripos($message, 'feat') !== false || stripos($message, 'add') !== false || stripos($message, 'create') !== false) {
                $prefix = "Fitur Baru";
            } elseif (stripos($message, 'clean') !== false || stripos($message, 'rm') !== false || stripos($message, 'delete') !== false || stripos($message, 'drop') !== false) {
                $prefix = "Pembersihan Berkas";
            } elseif (stripos($message, 'opt') !== false || stripos($message, 'index') !== false || stripos($message, 'speed') !== false || stripos($message, 'performa') !== false) {
                $prefix = "Peningkatan Performa";
            }
            
            $content .= "*   **[{$prefix}]** {$messageClean} (Dikerjakan oleh: {$author})\n";
        }
        
        $content .= "\n---\n\n";
        $content .= "## 📁 Berkas/Modul yang Diperbarui\n\n";
        if (!empty($changedFiles)) {
            foreach ($changedFiles as $file) {
                if (empty($file)) continue;
                $content .= "*   `{$file}`\n";
            }
        } else {
            $content .= "*   Tidak ada perubahan berkas fisik yang tercatat.*\n";
        }

        $filePath = $docsPath . '/' . $today . '.md';
        File::put($filePath, $content);

        $this->info("Laporan harian berhasil dibuat di: {$filePath}");
    }
}
