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
                'mata_kuliah' => $krs->mataKuliah->nama_mk,
                'kode_mk' => $krs->mataKuliah->kode_mk,
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
            // Using exact 'pertemuan' match from the Presensi record
            $attendance = $presensis->filter(function ($p) use ($i) {
                return $p->pertemuan == $i;
            })->first();

            $status = 'Belum Dimulai';
            $statusClass = 'bg-gray-100 text-gray-500';

            if ($meetingDate->isPast() || $attendance) {
                if ($attendance) {
                    $status = $attendance->status; // Hadir, Izin, Sakit, Alpa
                    $statusClass = match ($status) {
                        'Hadir' => 'bg-green-100 text-green-700',
                        'Izin' => 'bg-blue-100 text-blue-700',
                        'Sakit' => 'bg-yellow-100 text-yellow-700',
                        'Alpa' => 'bg-red-100 text-red-700',
                        default => 'bg-gray-100 text-gray-600'
                    };
                } else if ($meetingDate->isPast()) {
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

            $assignments = $tugas->map(function ($t) use ($meetingDate, $mahasiswa) {
                // Check if mahasiswa already submitted
                $submission = \App\Models\TugasSubmission::where('tugas_id', $t->id)
                    ->where('mahasiswa_id', $mahasiswa->id)
                    ->first();
                
                return [
                    'id' => $t->id,
                    'title' => $t->title,
                    'description' => $t->description, // Don't strip HTML tags, let the view render them properly
                    'deadline' => $t->due_date ? \Carbon\Carbon::parse($t->due_date)->format('d M Y') : $meetingDate->copy()->addDays(7)->format('d M Y'),
                    'file_url' => $t->file_path ? route('mahasiswa.tugas.download', $t->id) : null,
                    'submission_type' => $t->submission_type ?? 'any',
                    'submitted' => $submission ? true : false,
                    'submitted_at' => $submission ? $submission->created_at->format('d M Y H:i') : null,
                    'score' => $submission ? $submission->score : null,
                    'comments' => $submission ? $submission->comments : null,
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

            // Prepare attendance data for display
            $attendanceStatus = null;
            $attendanceData = null;
            
            if ($attendance) {
                $attendanceStatus = strtolower($attendance->status); // hadir, izin, sakit, alpa
                $attendanceData = [
                    'presence_mode' => $attendance->presence_mode,
                    'distance_meters' => $attendance->distance_meters,
                    'reason_category' => $attendance->reason_category,
                    'reason_detail' => $attendance->reason_detail,
                    'waktu' => $attendance->created_at ? $attendance->created_at->format('d M Y H:i') : null,
                ];
            }
            // Don't set status for past meetings - let view handle "Belum Absen" display

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
                'attendance_status' => $attendanceStatus,
                'attendance_data' => $attendanceData,
            ];
        }

        $classInfo = [
            'id' => $kelas->id,
            'name' => $kelas->mataKuliah->nama_mk,
            'code' => $kelas->mataKuliah->kode_mk,
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
