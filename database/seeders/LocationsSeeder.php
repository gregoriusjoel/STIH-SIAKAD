<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\DB;
use App\Models\Country;
use App\Models\Province;
use App\Models\City;

class LocationsSeeder extends Seeder
{
    public function run()
    {
        $dir = base_path('master');
        $fs = new Filesystem();
        $countryPath = $dir . DIRECTORY_SEPARATOR . 'Country-data.csv';
        $provincesPath = $dir . DIRECTORY_SEPARATOR . 'provinces.csv';
        $citiesPath = $dir . DIRECTORY_SEPARATOR . 'cities.csv';

        if (! $fs->exists($provincesPath) || ! $fs->exists($citiesPath)) {
            $this->command->info('CSV files not found in master/. Skipping locations seeder.');
            return;
        }

        $this->command->info('Importing locations from CSV...');

        // parse provinces
        $provinces = [];
        $content = $fs->get($provincesPath);
        $lines = preg_split('/\r?\n/', trim($content));
        $headers = [];
        foreach ($lines as $i => $line) {
            $row = str_getcsv($line);
            if ($i === 0) { $headers = $row; continue; }
            if (count($row) === 0 || trim(implode('', $row)) === '') { continue; }
            $data = array_combine($headers, $row);
            $provinces[$data['province_code']] = [
                'id' => $data['id'] ?? null,
                'region_code' => $data['region_code'] ?? null,
                'province_code' => $data['province_code'] ?? null,
                'province' => $data['province'] ?? null,
                'cities' => [],
            ];
        }

        // parse cities
        $content = $fs->get($citiesPath);
        $lines = preg_split('/\r?\n/', trim($content));
        $headers = [];
        foreach ($lines as $i => $line) {
            $row = str_getcsv($line);
            if ($i === 0) { $headers = $row; continue; }
            if (count($row) === 0 || trim(implode('', $row)) === '') { continue; }
            $data = array_combine($headers, $row);
            $provCode = $data['province_code'] ?? null;
            if ($provCode && isset($provinces[$provCode])) {
                $provinces[$provCode]['cities'][] = [
                    'id' => $data['id'] ?? null,
                    'city_code' => $data['city_code'] ?? null,
                    'city' => $data['city'] ?? null,
                ];
            }
        }

        // parse all countries (Country-data.csv) and insert into countries table
        if ($fs->exists($countryPath)) {
            $content = $fs->get($countryPath);
            $lines = preg_split('/\r?\n/', trim($content));
            $headers = [];
            DB::transaction(function () use ($lines, &$headers) {
                foreach ($lines as $i => $line) {
                    $row = str_getcsv($line);
                    if ($i === 0) { $headers = $row; continue; }
                    if (count($row) === 0 || trim(implode('', $row)) === '') { continue; }
                    $data = array_combine($headers, $row);
                    $name = $data['country'] ?? null;
                    if (! $name) continue;
                    Country::firstOrCreate(
                        ['name' => $name],
                        ['code' => $data['code'] ?? null, 'meta' => $data]
                    );
                }
            });
            $this->command->info('Imported countries from Country-data.csv');
        }

        // find (or create) Indonesia country to attach provinces/cities
        $indonesia = Country::whereRaw('LOWER(name) = ?', ['indonesia'])->first();
        if (! $indonesia) {
            $indonesia = Country::firstOrCreate(['name' => 'Indonesia']);
        }

        DB::transaction(function () use ($indonesia, $provinces) {
            foreach ($provinces as $prov) {
                $provModel = Province::firstOrCreate(
                    ['code' => $prov['province_code'], 'name' => $prov['province']],
                    ['country_id' => $indonesia->id, 'region_code' => $prov['region_code'], 'meta' => $prov]
                );

                foreach ($prov['cities'] as $c) {
                    City::firstOrCreate(
                        ['code' => $c['city_code'] ?? null, 'name' => $c['city'] ?? null, 'province_id' => $provModel->id],
                        ['meta' => $c]
                    );
                }
            }
        });

        $this->command->info('Locations imported.');
    }
}
