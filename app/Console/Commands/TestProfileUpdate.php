<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Mahasiswa;
use App\Models\User;
use App\Models\ParentModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class TestProfileUpdate extends Command
{
    protected $signature = 'test:profile-update {--commit}';
    protected $description = 'Test profile update flow for debugging';

    public function handle()
    {
        $this->newLine();
        $this->line(str_repeat('=', 70));
        $this->info('  PROFILE UPDATE TEST — ' . now()->format('Y-m-d H:i:s'));
        $this->line(str_repeat('=', 70));

        // ─── Step 1: Find the student ───
        $mahasiswa = Mahasiswa::where('nim', '2024010001')->first();
        if (!$mahasiswa) {
            $this->error('Mahasiswa NIM 2024010001 tidak ditemukan!');
            return 1;
        }

        $user = User::find($mahasiswa->user_id);
        if (!$user) {
            $this->error("User ID {$mahasiswa->user_id} tidak ditemukan!");
            return 1;
        }

        $this->info("✅ Found: {$user->name} (NIM: {$mahasiswa->nim}, User ID: {$user->id})");
        $this->line("   Status akun: {$mahasiswa->status_akun}");
        $this->line("   Profile complete: " . ($mahasiswa->isProfileComplete() ? 'YES' : 'NO'));

        // ─── Step 2: Show current data ──
        $fieldsToCheck = [
            'no_hp', 'alamat', 'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin',
            'agama', 'status_sipil', 'kota', 'kecamatan', 'desa', 'provinsi',
            'jenis_sekolah', 'jurusan_sekolah', 'tahun_lulus', 'nilai_kelulusan',
            'file_ijazah', 'file_transkrip', 'file_kk', 'file_ktp',
        ];

        $this->newLine();
        $this->line('── Current Field Values ──');
        $rows = [];
        foreach ($fieldsToCheck as $field) {
            $val = $mahasiswa->$field;
            if (is_array($val)) $val = json_encode($val);
            $status = empty($val) ? '❌ EMPTY' : '✅';
            $rows[] = [$field, $status, $val ?? '(null)'];
        }
        $this->table(['Field', 'Status', 'Value'], $rows);

        // ─── Step 3: Direct model update test ──
        $this->newLine();
        $this->line('── Test 1: Direct Model Update (with rollback) ──');

        $testData = [
            'no_hp' => '081234567890',
            'alamat' => 'Jl. Test No. 123',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '2000-01-15',
            'jenis_kelamin' => 'Laki-Laki',
            'agama' => 'Islam',
            'status_sipil' => 'Belum Menikah',
            'kota' => 'Jakarta Selatan',
            'kecamatan' => 'Kebayoran Baru',
            'desa' => 'Senayan',
            'provinsi' => 'DKI Jakarta',
            'jenis_sekolah' => '1 - Umum',
            'jurusan_sekolah' => 'SMA',
            'tahun_lulus' => '2024',
            'nilai_kelulusan' => '85.50',
        ];

        DB::beginTransaction();
        try {
            $result = $mahasiswa->update($testData);
            $this->line("  update() returned: " . var_export($result, true));
            $changes = $mahasiswa->getChanges();
            $this->line("  Changed fields count: " . count($changes));

            $mahasiswa->refresh();
            $allSaved = true;
            foreach ($testData as $key => $expected) {
                $actual = $mahasiswa->$key;
                if ($actual != $expected) {
                    $this->error("  ❌ {$key}: expected '{$expected}' but got '{$actual}'");
                    $allSaved = false;
                }
            }

            if ($allSaved) {
                $this->info('  ✅ All test fields saved correctly to database!');
            }

            $this->line('  isProfileComplete() after update: ' . ($mahasiswa->isProfileComplete() ? 'YES ✅' : 'NO ❌'));

            if (!$mahasiswa->isProfileComplete()) {
                $this->warn('  Still incomplete because:');
                $missing = $mahasiswa->getMissingProfileFields();
                foreach ($missing as $f => $info) {
                    $this->line("    - {$info['label']} ({$f}) [tab: {$info['tab']}]");
                }
            }
        } catch (\Exception $e) {
            $this->error("  Exception: " . $e->getMessage());
        }

        DB::rollBack();
        $this->line('  (Transaction rolled back)');

        // ─── Step 4: Test ConvertEmptyStringsToNull middleware ──
        $this->newLine();
        $this->line('── Test 2: ConvertEmptyStringsToNull Middleware ──');

        $request = new Request($testData);
        $middleware = new \App\Http\Middleware\ConvertEmptyStringsToNull();

        // Test with filled values
        $this->line('  With FILLED values:');
        $nullified = [];
        $middleware->handle($request, function ($req) use ($testData, &$nullified) {
            foreach ($testData as $key => $expected) {
                $actual = $req->input($key);
                if ($actual === null && $expected !== null) {
                    $nullified[] = $key;
                }
            }
            return new \Illuminate\Http\Response('ok');
        });
        if (empty($nullified)) {
            $this->info('  ✅ No fields were nullified — middleware OK for filled data');
        } else {
            $this->error('  ❌ These fields were nullified: ' . implode(', ', $nullified));
        }

        // Test with empty string values (simulating empty form submission)
        $this->newLine();
        $this->line('  With EMPTY STRING values (simulating empty form):');
        $emptyData = [];
        foreach ($testData as $key => $val) {
            $emptyData[$key] = ''; // All empty strings
        }
        $emptyRequest = new Request($emptyData);
        $nullifiedEmpty = [];
        $middleware->handle($emptyRequest, function ($req) use ($testData, &$nullifiedEmpty) {
            foreach (array_keys($testData) as $key) {
                $actual = $req->input($key);
                if ($actual === null) {
                    $nullifiedEmpty[] = $key;
                }
            }
            return new \Illuminate\Http\Response('ok');
        });
        if (!empty($nullifiedEmpty)) {
            $this->warn('  ⚠️  Fields converted to NULL: ' . implode(', ', $nullifiedEmpty));
            $this->line('  Fields left as empty string: ' . implode(', ', array_diff(array_keys($testData), $nullifiedEmpty)));
        } else {
            $this->info('  ✅ No fields converted to null');
        }

        // ─── Step 5: Test full controller validation ──
        $this->newLine();
        $this->line('── Test 3: Full Controller Validation ──');

        Auth::login($user);
        $this->line("  Logged in as: {$user->name}");

        $fullRequestData = array_merge($testData, [
            'name' => $user->name,
            'email_pribadi' => '',
            'nama_ayah' => 'Budi Santoso',
            'pendidikan_ayah' => 'S1',
            'pekerjaan_ayah' => 'PNS',
            'agama_ayah' => 'Islam',
            'nama_ibu' => 'Siti Aminah',
            'pendidikan_ibu' => 'S1',
            'pekerjaan_ibu' => 'Guru',
            'agama_ibu' => 'Islam',
            'alamat_ayah' => 'Jl. Ayah No. 1',
            'kota_ayah' => 'Jakarta',
            'kecamatan_ayah' => 'Menteng',
            'desa_ayah' => 'Menteng Atas',
            'propinsi_ayah' => 'DKI Jakarta',
            'handphone_ayah' => '08111222333',
            'alamat_ibu' => 'Jl. Ibu No. 2',
            'kota_ibu' => 'Jakarta',
            'kecamatan_ibu' => 'Menteng',
            'desa_ibu' => 'Menteng Atas',
            'propinsi_ibu' => 'DKI Jakarta',
            'handphone_ibu' => '08222333444',
        ]);

        DB::beginTransaction();
        try {
            // Create proper HTTP request
            $httpRequest = Request::create('/mahasiswa/profil', 'PUT', $fullRequestData);
            $httpRequest->setLaravelSession(app('session.store'));
            $httpRequest->setUserResolver(function () use ($user) {
                return $user;
            });

            // First run through middleware
            $middleware->handle($httpRequest, function ($req) {
                return new \Illuminate\Http\Response('ok');
            });

            // Now try validation
            $controller = app(\App\Http\Controllers\Mahasiswa\ProfilController::class);
            $response = $controller->update($httpRequest);

            $statusCode = $response->getStatusCode();
            $this->line("  Response status: {$statusCode}");

            if ($statusCode === 302) {
                $redirectUrl = $response->headers->get('Location');
                $this->line("  Redirect to: {$redirectUrl}");

                if (str_contains($redirectUrl, 'profil')) {
                    $this->info('  ✅ Redirects to profil page (expected success behavior)');
                }
            }

            // Check session for success/error
            $session = $httpRequest->session();
            if ($session->has('success')) {
                $this->info("  ✅ Session success: " . $session->get('success'));
            }
            if ($session->has('errors')) {
                $errors = $session->get('errors');
                if ($errors && method_exists($errors, 'all')) {
                    $this->error('  ❌ Session validation errors:');
                    foreach ($errors->all() as $err) {
                        $this->line("    - {$err}");
                    }
                }
            }

            // Verify DB
            $mahasiswa->refresh();
            $this->newLine();
            $this->line('  Database verification after controller update:');
            $dbRows = [];
            foreach ($testData as $key => $expected) {
                $actual = $mahasiswa->$key;
                $match = ($actual == $expected) ? '✅' : '❌';
                $dbRows[] = [$key, $expected, $actual ?? '(null)', $match];
            }
            $this->table(['Field', 'Expected', 'Actual', 'Match'], $dbRows);

            $this->line('  isProfileComplete(): ' . ($mahasiswa->isProfileComplete() ? 'YES ✅' : 'NO ❌'));

        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->error('  ❌ VALIDATION EXCEPTION:');
            foreach ($e->errors() as $field => $messages) {
                foreach ($messages as $msg) {
                    $this->line("    [{$field}] {$msg}");
                }
            }
        } catch (\Exception $e) {
            $this->error('  ❌ EXCEPTION: ' . get_class($e));
            $this->line("    Message: " . $e->getMessage());
            $this->line("    File: " . $e->getFile() . ':' . $e->getLine());
        }

        if ($this->option('commit')) {
            DB::commit();
            $this->info('  (Transaction COMMITTED — changes saved permanently to database!)');
        } else {
            DB::rollBack();
            $this->line('  (Transaction rolled back — no permanent changes)');
        }

        // ─── Step 5.5: Test Single-Parent Completeness logic ───
        $this->newLine();
        $this->line('── Test 4: Single-Parent Completeness Verification ──');
        DB::beginTransaction();
        try {
            $mahasiswa->refresh();
            // Fill all base required fields, KTP, School, Documents
            $completeData = [
                'no_hp' => '081234567890',
                'alamat' => 'Jl. Test No. 123',
                'tempat_lahir' => 'Jakarta',
                'tanggal_lahir' => '2000-01-15',
                'jenis_kelamin' => 'Laki-Laki',
                'agama' => 'Islam',
                'status_sipil' => 'Belum Menikah',
                'kota' => 'Jakarta Selatan',
                'kecamatan' => 'Kebayoran Baru',
                'desa' => 'Senayan',
                'provinsi' => 'DKI Jakarta',
                
                'alamat_ktp' => 'Jl. Ktp No. 123',
                'provinsi_ktp' => 'DKI Jakarta',
                'kota_ktp' => 'Jakarta Selatan',
                'kecamatan_ktp' => 'Kebayoran Baru',
                'desa_ktp' => 'Senayan',
                
                'jenis_sekolah' => '1 - Umum',
                'jurusan_sekolah' => 'SMA',
                'tahun_lulus' => '2024',
                'nilai_kelulusan' => '85.50',
                
                'file_ijazah' => ['fake_ijazah.pdf'],
                'file_transkrip' => ['fake_transkrip.pdf'],
                'file_kk' => ['fake_kk.pdf'],
                'file_ktp' => ['fake_ktp.pdf'],
            ];

            $mahasiswa->update($completeData);

            // Situation A: Mother is fully complete, Father and Wali are empty
            $motherComplete = [
                'nama_ibu' => 'Ibu Kandung',
                'pendidikan_ibu' => 'S1',
                'pekerjaan_ibu' => 'Swasta',
                'agama_ibu' => 'Islam',
                'alamat_ibu' => 'Jl. Ibu No. 123',
                'kota_ibu' => 'Jakarta Selatan',
                'propinsi_ibu' => 'DKI Jakarta',
                'kecamatan_ibu' => 'Kebayoran Baru',
                'desa_ibu' => 'Senayan',
                'handphone_ibu' => '081111111111',
                
                'nama_ayah' => null,
                'pendidikan_ayah' => null,
                'pekerjaan_ayah' => null,
                'agama_ayah' => null,
                'alamat_ayah' => null,
                'kota_ayah' => null,
                'propinsi_ayah' => null,
                'kecamatan_ayah' => null,
                'desa_ayah' => null,
                'handphone_ayah' => null,
                
                'nama_wali' => null,
            ];

            $parent = $mahasiswa->parents()->first();
            if (!$parent) {
                $parent = $mahasiswa->parents()->create([
                    'user_id' => $mahasiswa->user_id,
                ]);
            }
            $parent->update($motherComplete);
            $mahasiswa->refresh();

            $isMotherComplete = $mahasiswa->isProfileComplete();
            $this->line('  Situation A (Only Mother completed, Father/Wali empty):');
            $this->line('    isProfileComplete(): ' . ($isMotherComplete ? 'YES ✅' : 'NO ❌'));
            if (!$isMotherComplete) {
                $this->warn('    Missing fields: ' . implode(', ', array_keys($mahasiswa->getMissingProfileFields())));
            }

            // Situation B: Mother is complete, but Father is partially filled (missing fields)
            $fatherPartial = [
                'nama_ayah' => 'Ayah Kandung',
                'pendidikan_ayah' => 'SMA',
            ];
            $parent->update($fatherPartial);
            $mahasiswa->refresh();

            $isPartialComplete = $mahasiswa->isProfileComplete();
            $this->line('  Situation B (Mother complete, Father partially filled):');
            $this->line('    isProfileComplete(): ' . ($isPartialComplete ? 'YES ✅' : 'NO ❌'));
            if (!$isPartialComplete) {
                $missingKeys = array_keys($mahasiswa->getMissingProfileFields());
                $this->info('    ✅ Correctly identified missing Father fields: ' . implode(', ', $missingKeys));
            }

        } catch (\Exception $e) {
            $this->error("  Exception in Test 4: " . $e->getMessage());
        }
        DB::rollBack();
        $this->line('  (Transaction rolled back — no permanent changes)');

        // ─── Step 6: Config check ──
        $this->newLine();
        $this->line('── Session & Config ──');
        $configRows = [
            ['SESSION_DRIVER', config('session.driver')],
            ['SESSION_DOMAIN', "'" . config('session.domain') . "'"],
            ['SESSION_SECURE', var_export(config('session.secure'), true)],
            ['SESSION_SAME_SITE', config('session.same_site')],
            ['APP_URL', config('app.url')],
            ['APP_DEBUG', var_export(config('app.debug'), true)],
        ];
        $this->table(['Setting', 'Value'], $configRows);

        $this->newLine();
        $this->info('Test complete!');
        return 0;
    }
}
