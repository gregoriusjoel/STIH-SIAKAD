<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Filesystem\Filesystem;
use App\Models\Religion;

class ReligionSeeder extends Seeder
{
    public function run()
    {
        $fs = new Filesystem();
        $path = base_path('master/Religion.csv');

        if (! $fs->exists($path)) {
            $this->command->info("Religion.csv not found at $path, skipping.");
            return;
        }

        $content = $fs->get($path);
        $lines = preg_split('/\r?\n/', trim($content));
        foreach ($lines as $i => $line) {
            // Skip header
            if ($i === 0) { continue; }
            if (trim($line) === '') { continue; }

            // CSV uses semicolon delimiter
            $parts = array_map('trim', explode(';', $line));
            $code = $parts[0] ?? null;
            $name = $parts[1] ?? null;

            if (! $name) { continue; }

            Religion::updateOrCreate(
                ['code' => $code],
                ['name' => $name]
            );
        }

        $this->command->info('Religions seeded.');
    }
}
