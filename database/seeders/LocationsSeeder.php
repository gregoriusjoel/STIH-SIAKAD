<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class LocationsSeeder extends Seeder
{
    public function run()
    {
        $fs = new Filesystem();
        $base = base_path('master');

        // Provinces
        $provPath = $base . '/provinces.csv';
        if ($fs->exists($provPath) && Schema::hasTable('provinces')) {
            $content = $fs->get($provPath);
            $lines = preg_split('/\r?\n/', trim($content));
            foreach ($lines as $i => $line) {
                if ($i === 0) continue;
                if (trim($line) === '') continue;
                $parts = str_getcsv($line);
                $code = $parts[2] ?? null; // province_code
                $name = $parts[3] ?? null; // province
                if (!$code || !$name) continue;
                DB::table('provinces')->updateOrInsert(
                    ['code' => trim($code)],
                    ['name' => trim($name), 'created_at' => now(), 'updated_at' => now()]
                );
            }
            $this->command->info('Provinces seeded.');
        } else {
            $this->command->info('Provinces CSV or table missing; skipping provinces.');
        }

        // Cities
        $cityPath = $base . '/cities.csv';
        if ($fs->exists($cityPath) && Schema::hasTable('cities')) {
            $content = $fs->get($cityPath);
            $lines = preg_split('/\r?\n/', trim($content));
            foreach ($lines as $i => $line) {
                if ($i === 0) continue;
                if (trim($line) === '') continue;
                $parts = str_getcsv($line);
                // Expected columns: id,province_code,city_code,city_name,...
                $provinceCode = $parts[1] ?? null;
                $cityCode = $parts[2] ?? null;
                $cityName = $parts[3] ?? null;
                if (!$cityCode || !$cityName) continue;
                // Try to find province id
                $province = null;
                if (Schema::hasTable('provinces')) {
                    $province = DB::table('provinces')->where('code', trim($provinceCode))->first();
                }
                $provinceId = $province->id ?? null;
                DB::table('cities')->updateOrInsert(
                    ['code' => trim($cityCode)],
                    ['name' => trim($cityName), 'province_id' => $provinceId, 'created_at' => now(), 'updated_at' => now()]
                );
            }
            $this->command->info('Cities seeded.');
        } else {
            $this->command->info('Cities CSV or table missing; skipping cities.');
        }

        // Villages (optional)
        $vPath = $base . '/villages.csv';
        if ($fs->exists($vPath) && Schema::hasTable('villages')) {
            $content = $fs->get($vPath);
            $lines = preg_split('/\r?\n/', trim($content));
            foreach ($lines as $i => $line) {
                if ($i === 0) continue;
                if (trim($line) === '') continue;
                $parts = str_getcsv($line);
                $vName = $parts[3] ?? null;
                $cityCode = $parts[2] ?? null;
                if (!$vName) continue;
                $city = null;
                if (Schema::hasTable('cities')) {
                    $city = DB::table('cities')->where('code', trim($cityCode))->first();
                }
                $cityId = $city->id ?? null;
                DB::table('villages')->updateOrInsert(
                    ['name' => trim($vName), 'city_id' => $cityId],
                    ['created_at' => now(), 'updated_at' => now()]
                );
            }
            $this->command->info('Villages seeded.');
        } else {
            $this->command->info('Villages CSV or table missing; skipping villages.');
        }
    }
}
