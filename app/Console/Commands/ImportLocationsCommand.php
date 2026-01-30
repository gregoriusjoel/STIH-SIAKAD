<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\DB;
use App\Models\Country;
use App\Models\Province;
use App\Models\City;

class ImportLocationsCommand extends Command
{
    protected $signature = 'import:locations {--dir=master} {--out=storage/app/locations.json}';
    protected $description = 'Import Country/Province/City CSV files from master folder and produce hierarchical JSON.';

    public function handle()
    {
        $dir = base_path($this->option('dir'));
        $out = base_path($this->option('out'));

        $filesys = new Filesystem();

        if (! $filesys->exists($dir)) {
            $this->error("Directory $dir not found.");
            return 1;
        }

        $countryPath = $dir . DIRECTORY_SEPARATOR . 'Country-data.csv';
        $provincesPath = $dir . DIRECTORY_SEPARATOR . 'provinces.csv';
        $citiesPath = $dir . DIRECTORY_SEPARATOR . 'cities.csv';

        if (! $filesys->exists($countryPath) && ! $filesys->exists($provincesPath) && ! $filesys->exists($citiesPath)) {
            $this->error('No CSV files found in master folder.');
            return 1;
        }

        $this->info('Parsing CSVs...');

        $countries = [];

        // Parse Country-data.csv (optional meta per country)
        if ($filesys->exists($countryPath)) {
            $content = $filesys->get($countryPath);
            $lines = preg_split('/\r?\n/', trim($content));
            $headers = [];
            foreach ($lines as $i => $line) {
                $row = str_getcsv($line);
                if ($i === 0) { $headers = $row; continue; }
                if (count($row) === 0 || trim(implode('', $row)) === '') { continue; }
                $assoc = array_combine($headers, $row);
                $name = $assoc['country'] ?? null;
                if (! $name) { continue; }
                $countries[$this->normalize($name)] = [
                    'name' => $name,
                    'meta' => $assoc,
                    'provinces' => [],
                ];
            }
        }

        // Parse provinces.csv
        $provinces = [];
        if ($filesys->exists($provincesPath)) {
            $content = $filesys->get($provincesPath);
            $lines = preg_split('/\r?\n/', trim($content));
            $headers = [];
            foreach ($lines as $i => $line) {
                $row = str_getcsv($line);
                if ($i === 0) { $headers = $row; continue; }
                if (count($row) === 0 || trim(implode('', $row)) === '') { continue; }
                // expecting columns: id,region_code,province_code,province,created_at,updated_at
                $data = array_combine($headers, $row);
                $prov = [
                    'id' => $data['id'] ?? null,
                    'region_code' => $data['region_code'] ?? null,
                    'province_code' => $data['province_code'] ?? null,
                    'province' => $data['province'] ?? null,
                    'cities' => [],
                ];
                $provinces[$prov['province_code']] = $prov;
            }
        }

        // Parse cities.csv and attach to provinces by province_code
        if ($filesys->exists($citiesPath)) {
            $content = $filesys->get($citiesPath);
            $lines = preg_split('/\r?\n/', trim($content));
            $headers = [];
            foreach ($lines as $i => $line) {
                $row = str_getcsv($line);
                if ($i === 0) { $headers = $row; continue; }
                if (count($row) === 0 || trim(implode('', $row)) === '') { continue; }
                // id,region_code,province_code,city_code,city,created_at,updated_at
                $data = array_combine($headers, $row);
                $provCode = $data['province_code'] ?? null;
                $cityName = $data['city'] ?? null;
                if ($provCode && isset($provinces[$provCode])) {
                    $provinces[$provCode]['cities'][] = [
                        'id' => $data['id'] ?? null,
                        'city_code' => $data['city_code'] ?? null,
                        'city' => $cityName,
                    ];
                }
            }
        }

        // If countries is empty, create a default country 'Indonesia' and attach provinces
        if (empty($countries)) {
            $countries['indonesia'] = [
                'name' => 'Indonesia',
                'meta' => [],
                'provinces' => [],
            ];
        }

        // Attach provinces to appropriate country (best-effort: attach all provinces to Indonesia)
        foreach ($provinces as $prov) {
            // try to detect country by province name? default to Indonesia
            $countries['indonesia']['provinces'][] = $prov;
        }

        $outDir = dirname($out);
        if (! $filesys->exists($outDir)) { $filesys->makeDirectory($outDir, 0755, true); }

        $json = array_values($countries);
        $filesys->put($out, json_encode($json, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));

        $this->info("Wrote locations to $out");

        // Persist to database
        $this->info('Importing locations into database...');
        DB::beginTransaction();
        try {
            foreach ($json as $countryData) {
                $country = Country::firstOrCreate(
                    ['name' => $countryData['name']],
                    ['code' => $countryData['meta']['country_code'] ?? null, 'meta' => $countryData['meta'] ?? null]
                );

                foreach ($countryData['provinces'] as $prov) {
                    // Ensure province is created/updated with the correct country_id.
                    $provModel = Province::updateOrCreate(
                        ['code' => $prov['province_code'] ?? null],
                        ['name' => $prov['province'] ?? null, 'country_id' => $country->id, 'region_code' => $prov['region_code'] ?? null, 'meta' => $prov]
                    );

                    if (! empty($prov['cities'])) {
                        foreach ($prov['cities'] as $c) {
                            // Ensure city is created/updated and linked to the correct province
                            City::updateOrCreate(
                                ['code' => $c['city_code'] ?? null, 'province_id' => $provModel->id],
                                ['name' => $c['city'] ?? null, 'meta' => $c]
                            );
                        }
                    }
                }
            }

            DB::commit();
            $this->info('Import completed successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error('Import failed: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }

    protected function normalize($s)
    {
        return strtolower(preg_replace('/[^a-z0-9]+/i', '-', trim($s)));
    }
}
