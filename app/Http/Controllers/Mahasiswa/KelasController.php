<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Krs;

class KelasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $mahasiswa = $user->mahasiswa;

        if (!$mahasiswa) {
            abort(403, 'Unauthorized access.');
        }

        // Fetch approved KRS records for the student
        // Using 'with' to eager load relationships and avoid N+1 problem
        $krsRecords = Krs::where('mahasiswa_id', $mahasiswa->id)
            ->whereIn('status', ['approved', 'disetujui'])
            ->with([
                'kelas',
                'mataKuliah',
                'kelasMataKuliah',
                'kelasMataKuliah.dosen',
                'kelas.jadwals' => function ($q) {
                    $q->where('status', 'active');
                }
            ])
            ->get();

        $classes = $krsRecords->map(function ($krs) {
            if (!$krs->mataKuliah) {
                return null;
            }

            $kelas = $krs->kelas;
            $jadwal = $kelas ? $kelas->jadwals->first() : null;
            $dosen = $krs->kelasMataKuliah ? $krs->kelasMataKuliah->dosen : null;

            return [
                'id' => $kelas ? $kelas->id : null,
                'mata_kuliah' => $krs->mataKuliah->nama,
                'kode_mk' => $krs->mataKuliah->kode,
                'sks' => $krs->mataKuliah->sks,
                'semester' => $krs->mataKuliah->semester,
                'section' => $kelas ? $kelas->section : '-',
                'dosen' => $dosen ? $dosen->nama : 'Belum ditentukan',
                'hari' => $jadwal ? $jadwal->hari : '-',
                'jam' => $jadwal ? substr($jadwal->jam_mulai, 0, 5) . ' - ' . substr($jadwal->jam_selesai, 0, 5) : '-',
                'ruangan' => $jadwal ? $jadwal->ruangan : '-',
            ];
        })->filter(function ($class) {
            return $class && !is_null($class['id']);
        });

        return view('page.mahasiswa.kelas.index', compact('classes'));
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $user = Auth::user();
        $mahasiswa = $user->mahasiswa;

        // Verify student has APPROVED KRS for this class
        $krs = Krs::where('mahasiswa_id', $mahasiswa->id)
            ->where('kelas_id', $id)
            ->whereIn('status', ['approved', 'disetujui'])
            ->firstOrFail();

        $kelas = $krs->kelas()->with([
            'mataKuliah',
            'dosen',
            'jadwals' => function ($q) {
                $q->where('status', 'active');
            }
        ])->first();

        $jadwal = $kelas->jadwals->first();

        // --- Meeting Calculation Logic (Replicated from LecturerController) ---
        $semesterAktif = \App\Models\Semester::where('status', 'aktif')->first()
            ?? \App\Models\Semester::latest()->first();

        $startDate = $semesterAktif && $semesterAktif->tanggal_mulai
            ? \Carbon\Carbon::parse($semesterAktif->tanggal_mulai)
            : now();

        $meetings = [];
        $totalPertemuan = 16;

        // Fetch student's attendance records for this class
        $presensis = \App\Models\Presensi::where('mahasiswa_id', $mahasiswa->id)
            ->where('kelas_mata_kuliah_id', $krs->kelas_mata_kuliah_id)
            ->get();

        // If we can't link via kelas_mata_kuliah_id easily (if krs doesn't have it set correctly), 
        // we might fallback to krs_id if Presensi has it. 
        // But assuming Presensi logic works, let's stick to what's available.
        // Actually, let's double check Presensi model relations. 
        // It has krs_id. So we can also query by krs_id.

        $presensis = \App\Models\Presensi::where('krs_id', $krs->id)->get();

        for ($i = 1; $i <= $totalPertemuan; $i++) {
            $meetingDate = $startDate->copy()->addDays(($i - 1) * 7);

            // Skip future meetings - only show meetings that have occurred or are happening today
            if ($meetingDate->isAfter(now()->endOfDay())) {
                continue;
            }

            // Find attendance for this specific date/meeting
            // Since we don't have 'pertemuan_ke' in Presensi, we might match by date range or just assume 
            // the presensi records map to weeks if we had that info. 
            // However, existing Presensi model has 'tanggal'.
            // Let's try to match by date (approximate) or if the logic is not yet robust, 
            // we will show "Belum Absen" for future, and "Tanpa Keterangan" for past if no record.

            // For now, simpler matching: 
            // If there is a presensi record with date matching this week's class date?
            // Or simpler: just list the meetings. Matching specific attendance is hard without 'pertemuan_ke' column in Presensi.
            // But let's check if we can match by date.

            $formattedDate = $meetingDate->format('Y-m-d');
            $attendance = $presensis->filter(function ($p) use ($formattedDate) {
                return $p->tanggal && \Carbon\Carbon::parse($p->tanggal)->format('Y-m-d') === $formattedDate;
            })->first();

            $status = 'Belum Dimulai';
            $statusClass = 'bg-gray-100 text-gray-500';

            if ($meetingDate->isPast()) {
                if ($attendance) {
                    $status = $attendance->status; // Hadir, Izin, Sakit, Alpa
                    $statusClass = match ($status) {
                        'Hadir' => 'bg-green-100 text-green-700',
                        'Izin' => 'bg-blue-100 text-blue-700',
                        'Sakit' => 'bg-yellow-100 text-yellow-700',
                        'Alpa' => 'bg-red-100 text-red-700',
                        default => 'bg-gray-100 text-gray-600'
                    };
                } else {
                    $status = 'Tanpa Keterangan'; // Or Alpa?
                    $statusClass = 'bg-red-50 text-red-600';
                }
            } else {
                $status = 'Belum Dimulai';
            }

            // Load actual materi from database for this pertemuan
            $materis = \App\Models\Materi::where('mata_kuliah_id', $kelas->mata_kuliah_id)
                ->where('pertemuan', $i)
                ->with('dosen')
                ->latest()
                ->get();

            $materials = $materis->map(function ($materi) {
                return [
                    'id' => $materi->id,
                    'name' => $materi->file_name,
                    'url' => route('mahasiswa.materi.download', $materi->id),
                    'type' => $materi->file_type,
                    'judul' => $materi->judul,
                    'deskripsi' => $materi->deskripsi,
                    'size' => $materi->file_size_human,
                    'uploaded_by' => $materi->dosen->name ?? 'Dosen',
                ];
            })->toArray();

            // Load tugas for this pertemuan
            $tugas = \App\Models\Tugas::where('mata_kuliah_id', $kelas->mata_kuliah_id)
                ->where('pertemuan', $i)
                ->with('dosen')
                ->latest()
                ->get();

            $assignments = $tugas->map(function ($t) use ($meetingDate) {
                return [
                    'id' => $t->id,
                    'title' => $t->judul,
                    'description' => $t->deskripsi,
                    'deadline' => $t->deadline ? \Carbon\Carbon::parse($t->deadline)->format('d M Y') : $meetingDate->copy()->addDays(7)->format('d M Y'),
                    'file_url' => $t->file_path ? route('mahasiswa.tugas.download', $t->id) : null,
                ];
            })->toArray();

            // Fetch Pertemuan record for method and link
            $pertemuanRecord = \App\Models\Pertemuan::where('kelas_mata_kuliah_id', $krs->kelas_mata_kuliah_id)
                ->where('nomor_pertemuan', $i)
                ->first();

            // Fallback if class_mata_kuliah_id in KRS might be null, try to find via Kelas
            if (!$pertemuanRecord && $kelas) {
                 $kmk = \App\Models\KelasMataKuliah::where('mata_kuliah_id', $kelas->mata_kuliah_id)
                    ->where('kode_kelas', $kelas->section)
                    ->first();
                 if ($kmk) {
                    $pertemuanRecord = \App\Models\Pertemuan::where('kelas_mata_kuliah_id', $kmk->id)
                        ->where('nomor_pertemuan', $i)
                        ->first();
                 }
            }

            $meetings[] = [
                'no' => $i,
                'label' => 'Pertemuan ' . $i,
                'date' => $meetingDate->locale('id')->isoFormat('D MMMM YYYY'),
                'time' => $jadwal ? substr($jadwal->jam_mulai, 0, 5) . ' - ' . substr($jadwal->jam_selesai, 0, 5) : '-',
                'status' => $status,
                'status_class' => $statusClass,
                'is_past' => $meetingDate->isPast(),
                'description' => 'Pada pertemuan ini akan dibahas mengenai pengantar mata kuliah, kontrak perkuliahan, dan pemaparan silabus selama satu semester.',
                'materials' => $materials,
                'assignments' => $assignments,
                'method' => $pertemuanRecord->metode_pengajaran ?? 'offline',
                'online_link' => $pertemuanRecord->online_meeting_link ?? null,
            ];
        }

        $classInfo = [
            'id' => $kelas->id,
            'name' => $kelas->mataKuliah->nama,
            'code' => $kelas->mataKuliah->kode,
            'sks' => $kelas->mataKuliah->sks,
            'semester' => $kelas->mataKuliah->semester,
            'section' => $kelas->section,
            'dosen' => $kelas->dosen->nama ?? 'Belum ditentukan',
            'day' => $jadwal ? $jadwal->hari : '-',
            'time' => $jadwal ? substr($jadwal->jam_mulai, 0, 5) . ' - ' . substr($jadwal->jam_selesai, 0, 5) : '-',
            'room' => $jadwal ? $jadwal->ruangan : '-',
            'progress' => 0 // TODO: calculate progress
        ];

        return view('page.mahasiswa.kelas.detail', compact('classInfo', 'meetings'));
    }
}
