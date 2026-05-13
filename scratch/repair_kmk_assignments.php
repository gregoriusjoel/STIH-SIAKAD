<?php

use App\Models\Jadwal;
use App\Models\KelasMataKuliah;
use App\Models\Semester;
use Illuminate\Support\Facades\DB;

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Starting KelasMataKuliah repair script...\n";

$activeSemester = Semester::where('status', 'aktif')->first()
    ?? Semester::where('is_active', true)->first()
    ?? Semester::latest()->first();

if (!$activeSemester) {
    die("No active semester found. Aborting.\n");
}

echo "Active Semester: {$activeSemester->nama_semester} (ID: {$activeSemester->id})\n";

$jadwals = Jadwal::where('status', 'active')
    ->with(['kelas.mataKuliah', 'kelas.kelasPerkuliahan'])
    ->get();

echo "Found " . $jadwals->count() . " active schedules.\n";

$count = 0;
foreach ($jadwals as $jadwal) {
    $kelas = $jadwal->kelas;
    if (!$kelas) continue;

    $kodeKelas = $kelas->kelasPerkuliahan?->kode_kelas ?? $kelas->resolved_kelas_name;
    
    $kmkData = [
        'mata_kuliah_id' => $kelas->mata_kuliah_id,
        'dosen_id' => $kelas->dosen_id,
        'semester_id' => $activeSemester->id,
        'kelas_perkuliahan_id' => $kelas->getRawOriginal('kelas_perkuliahan_id') ?? $kelas->kelas_perkuliahan_id,
        'kode_kelas' => $kodeKelas,
        'kapasitas' => $kelas->kapasitas ?? 40,
        'ruang' => $jadwal->ruangan ?: '-',
        'ruangan_id' => $jadwal->ruangan_id,
        'hari' => $jadwal->hari,
        'jam_mulai' => $jadwal->jam_mulai,
        'jam_selesai' => $jadwal->jam_selesai,
    ];

    // Try to find existing KMK
    $existing = KelasMataKuliah::where('mata_kuliah_id', $kelas->mata_kuliah_id)
        ->where('kode_kelas', $kodeKelas)
        ->where('dosen_id', $kelas->dosen_id)
        ->first()
        ?? KelasMataKuliah::where('mata_kuliah_id', $kelas->mata_kuliah_id)
        ->where('kode_kelas', $kodeKelas)
        ->first();

    try {
        if ($existing) {
            $existing->update($kmkData);
            echo "Updated KMK for MK ID: {$kelas->mata_kuliah_id}, Dosen ID: {$kelas->dosen_id}\n";
        } else {
            KelasMataKuliah::create($kmkData);
            echo "Created KMK for MK ID: {$kelas->mata_kuliah_id}, Dosen ID: {$kelas->dosen_id}\n";
        }
        $count++;
    } catch (\Exception $e) {
        echo "Error for MK ID: {$kelas->mata_kuliah_id}: " . $e->getMessage() . "\n";
    }
}

echo "Successfully processed $count KelasMataKuliah records.\n";
echo "Repair complete.\n";
