<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicEvent;
use App\Models\Semester;
use Illuminate\Http\Request;

class AcademicCalendarController extends Controller
{
    public function index()
    {
        $semesters = Semester::orderBy('tahun_ajaran', 'desc')->get();
        return view('admin.kalender-akademik.index', compact('semesters'));
    }

    public function getData(Request $request)
    {
        $events = [];
        $semesterId = $request->query('semester_id');

        // Get Semesters (filtered if requested)
        $semestersQuery = Semester::query();
        if ($semesterId) {
            // If specific semester is selected, maybe we only show that semester's period?
            // But usually semester period is long.
            $semestersQuery->where('id', $semesterId);
        }
        $semesters = $semestersQuery->get();

        foreach ($semesters as $semester) {
            // Event Semester (Blue/Purple)
            $events[] = [
                'id' => 'sem-' . $semester->id,
                'title' => $semester->nama_semester . ' ' . $semester->tahun_ajaran,
                'start' => $semester->tanggal_mulai,
                'end' => $semester->tanggal_selesai,
                'color' => '#3b82f6', // Blue
                'type' => 'semester',
                'display' => 'background', // Show as background event
                'className' => 'semester-bg-event',
                'extendedProps' => [
                    'semester_id' => $semester->id,
                    'krs_mulai' => $semester->krs_mulai,
                    'krs_selesai' => $semester->krs_selesai,
                ]
            ];
        }

        // Get Academic Events
        $query = AcademicEvent::active();

        if ($semesterId) {
            // Include events explicitly tied to the semester OR events with no semester_id
            // whose date range overlaps the semester period (so calendar-created events
            // without semester_id still show when filtering by that semester).
            $semester = Semester::find($semesterId);
            if ($semester) {
                $start = $semester->tanggal_mulai;
                $end = $semester->tanggal_selesai;

                $query->where(function ($q) use ($semesterId, $start, $end) {
                    $q->where('semester_id', $semesterId)
                        ->orWhere(function ($q2) use ($start, $end) {
                            $q2->whereNull('semester_id')
                                ->whereRaw('? <= end_date AND ? >= start_date', [$start, $end]);
                        });
                });
            } else {
                // Fallback: if semester not found, filter by exact semester_id value
                $query->where('semester_id', $semesterId);
            }
        }

        $academicEvents = $query->get();

        foreach ($academicEvents as $event) {
            $color = $this->getEventColor($event->event_type);

            $events[] = [
                'id' => $event->id, // Direct ID for easier update
                'title' => $event->title,
                'start' => $event->start_date,
                'end' => $event->end_date, // FullCalendar expects end date to be exclusive for all-day, but database stores inclusive.
                // We might need to adjust end date +1 day for display if it's allDay? 
                // For now, assume dates are stored as is. 
                'color' => $event->color ?? $color,
                'type' => $event->event_type,
                'extendedProps' => [
                    'description' => $event->description,
                    'event_id' => $event->id,
                    'semester_id' => $event->semester_id
                ]
            ];
        }

        return response()->json($events);
    }

    private function getEventColor($type)
    {
        return match ($type) {
            'perkuliahan' => '#3b82f6', // Blue
            'krs' => '#10b981', // Emerald
            'krs_perubahan' => '#059669', // Darker Emerald
            'uts' => '#f59e0b', // Amber
            'uas' => '#d97706', // Dark Amber
            'libur_akademik' => '#ef4444', // Red
            default => '#6b7280', // Gray
        };
    }

    public function downloadTemplate()
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="template_import_event.csv"',
        ];

        $columns = ['Title', 'StartDate (YYYY-MM-DD)', 'EndDate (YYYY-MM-DD)', 'Type', 'Description'];

        $callback = function () use ($columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            // Example Row
            fputcsv($file, ['Libur Semester', '2024-12-25', '2025-01-05', 'libur_akademik', 'Libur akhir semester ganjil']);

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function export()
    {
        $events = AcademicEvent::active()->get();
        return view('admin.kalender-akademik.pdf', compact('events'));
    }

    public function storeEvent(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'event_type' => 'required|in:krs,krs_perubahan,perkuliahan,uts,uas,libur_akademik,lainnya',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            // 'color' validation removed as we enforce it
            'semester_id' => 'nullable|exists:semesters,id',
        ]);

        // Enforce Auto Color
        $validated['color'] = $this->getEventColor($validated['event_type']);

        $event = AcademicEvent::create($validated);

        // Sync KRS dates to Semester if applicable
        if ($event->event_type === 'krs' && $event->semester_id) {
            $semester = Semester::find($event->semester_id);
            if ($semester) {
                $semester->update([
                    'krs_mulai' => $event->start_date,
                    'krs_selesai' => $event->end_date
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Event berhasil ditambahkan',
            'event' => $event
        ]);
    }

    public function updateEvent(Request $request, $id)
    {
        $event = AcademicEvent::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'event_type' => 'required|in:krs,krs_perubahan,perkuliahan,uts,uas,libur_akademik,lainnya',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            // 'color' validation removed
            'semester_id' => 'nullable|exists:semesters,id',
        ]);

        // Enforce Auto Color
        $validated['color'] = $this->getEventColor($validated['event_type']);

        $event->update($validated);

        // Sync KRS dates to Semester if applicable
        if ($event->event_type === 'krs' && $event->semester_id) {
            $semester = Semester::find($event->semester_id);
            if ($semester) {
                $semester->update([
                    'krs_mulai' => $event->start_date,
                    'krs_selesai' => $event->end_date
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Event berhasil diupdate',
            'event' => $event
        ]);
    }

    public function updateDate(Request $request, $id)
    {
        $event = AcademicEvent::findOrFail($id);

        $validated = $request->validate([
            'start' => 'required|date',
            'end' => 'nullable|date|after_or_equal:start',
        ]);

        $event->start_date = $validated['start'];
        // FullCalendar might send end date that is +1 day for inclusive all-day events. 
        // We generally store regular dates. 
        $event->end_date = $validated['end'] ?? $validated['start'];

        $event->save();

        // Sync KRS dates if applicable
        if ($event->event_type === 'krs' && $event->semester_id) {
            $semester = Semester::find($event->semester_id);
            if ($semester) {
                $semester->update([
                    'krs_mulai' => $event->start_date,
                    'krs_selesai' => $event->end_date
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Tanggal event berhasil diupdate'
        ]);
    }

    public function deleteEvent($id)
    {
        $event = AcademicEvent::findOrFail($id);
        $event->delete();

        return response()->json([
            'success' => true,
            'message' => 'Event berhasil dihapus'
        ]);
    }

    public function updateSemester(Request $request, $id)
    {
        $semester = Semester::findOrFail($id);

        $validated = $request->validate([
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'krs_mulai' => 'nullable|date',
            'krs_selesai' => 'nullable|date|after_or_equal:krs_mulai',
            'krs_dapat_diisi' => 'boolean',
        ]);

        $semester->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Semester berhasil diupdate',
            'semester' => $semester
        ]);
    }
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt,pdf|max:2048',
        ]);

        if ($request->input('pdf_text_content')) {
            return $this->processPdfText($request->input('pdf_text_content'));
        }

        $file = $request->file('file');
        $ext = strtolower($file->getClientOriginalExtension());

        if ($ext === 'pdf') {
            try {
                $text = \App\Services\SimplePdfParser::parseText($file->getPathname());
                return $this->processPdfText($text);
            } catch (\Exception $e) {
                return response()->json(['success' => false, 'message' => 'Gagal membaca PDF di server: ' . $e->getMessage()], 500);
            }
        }

        $handle = fopen($file->getPathname(), 'r');
        $header = fgetcsv($handle, 1000, ",");

        $count = 0;
        $errors = 0;

        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            try {
                if (count($data) < 4)
                    continue;

                $title = $data[0];
                $start = $data[1];
                $end = $data[2];
                $type = $data[3];
                $desc = $data[4] ?? '';
                $color = $data[5] ?? null;

                if (empty($title) || empty($start) || empty($end) || empty($type)) {
                    $errors++;
                    continue;
                }

                if (empty($color)) {
                    $color = $this->getEventColor($type);
                }

                AcademicEvent::create([
                    'title' => $title,
                    'start_date' => $start,
                    'end_date' => $end,
                    'event_type' => $type,
                    'description' => $desc,
                    'color' => $color,
                ]);

                $count++;
            } catch (\Exception $e) {
                $errors++;
            }
        }

        fclose($handle);

        return response()->json([
            'success' => true,
            'message' => "Berhasil mengimport {$count} event CSV. Gagal: {$errors}",
            'count' => $count
        ]);
    }

    private function processPdfText($text)
    {


        try {
            $count = 0;

            // Normalize spaces
            $text = preg_replace('/\s+/', ' ', $text);

            // New Logic: Find Date, then assume previous digits are 'No' and following string is 'Title'.
            // Pattern: Number (Optional) + DateString + Title
            // DateString examples: "09 - 11 Maret 2026", "30 Maret 2026", "27 Mei 2026"
            // We focus on finding "202x" and capturing the date before it.

            // Regex explanation:
            // 1. (?:(\d+)\s+)? -> Optional Number at start
            // 2. ([0-9].*?20\d\d) -> Date part starting with digit, ending with year
            // 3. \s+(.*?) -> Title
            // 4. (?=\s+\d+\s+[0-9]|$|Kegiatan) -> Lookahead for next entry start

            preg_match_all('/(?:(\d+)\s+)?([0-9].*?20\d\d)\s+(.*?)(?=\s+\d+\s+[0-9]|$)/i', $text, $matches, PREG_SET_ORDER);

            // If main regex fails, try lenient date matching
            if (empty($matches)) {
                preg_match_all('/([0-9][0-9\-\sA-Za-z]+20\d\d)\s+(.*?)(?=\s+[0-9]|$)/i', $text, $matches2, PREG_SET_ORDER);
                if (!empty($matches2)) {
                    $matches = [];
                    foreach ($matches2 as $m) {
                        // $m[1] is Date, $m[2] is Title. Mimic structure with dummy No
                        $matches[] = [0, '', $m[1], $m[2]];
                    }
                }
            }

            foreach ($matches as $match) {
                // Adjust index based on regex groups
                // If using first regex: [0]=Full, [1]=No, [2]=Date, [3]=Title
                // If using fallback: [0]=Full, [1]=No(dummy), [2]=Date, [3]=Title

                // Safety check array keys
                $dateStr = trim($match[2] ?? '');
                $title = trim($match[3] ?? '');

                if (empty($dateStr))
                    continue;

                // Cleanup Title
                $title = str_ireplace('Kegiatan', '', $title);
                $title = trim($title);
                if (strlen($title) < 3)
                    continue; // too short

                $dates = $this->parsePdfDate($dateStr);
                if (!$dates)
                    continue;

                $type = 'lainnya';
                $t = strtolower($title);
                if (str_contains($t, 'libur') || str_contains($t, 'cuti'))
                    $type = 'libur_akademik';
                else if (str_contains($t, 'krs'))
                    $type = 'krs';
                else if (str_contains($t, 'uts'))
                    $type = 'uts';
                else if (str_contains($t, 'uas'))
                    $type = 'uas';
                else if (str_contains($t, 'kuliah') || str_contains($t, 'pertemuan'))
                    $type = 'perkuliahan';

                AcademicEvent::create([
                    'title' => $title,
                    'start_date' => $dates['start'],
                    'end_date' => $dates['end'],
                    'event_type' => $type,
                    'description' => 'Imported from PDF',
                    'color' => $this->getEventColor($type)
                ]);
                $count++;
            }

            if ($count === 0) {
                return response()->json([
                    'success' => false,
                    'message' => "Gagal mengenali format. Text awal: " . substr($text, 0, 100),
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => "Berhasil membaca PDF! {$count} event ditemukan dan diimport.",
                'count' => $count
            ]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal membaca PDF: ' . $e->getMessage()], 500);
        }
    }

    private function parsePdfDate($str)
    {
        // Handle: "09 - 11 Maret 2026"
        // Handle: "30 Maret 2026"
        // Handle: "30 Maret - 15 Mei 2026"

        $months = [
            'Januari' => '01',
            'Februari' => '02',
            'Maret' => '03',
            'April' => '04',
            'Mei' => '05',
            'Juni' => '06',
            'Juli' => '07',
            'Agustus' => '08',
            'September' => '09',
            'Oktober' => '10',
            'November' => '11',
            'Desember' => '12'
        ];

        // Normalize spaces
        $str = preg_replace('/\s+/', ' ', $str);

        // Check for " - "
        if (str_contains($str, '-')) {
            $parts = explode('-', $str);
            $p1 = trim($parts[0]); // "09" or "30 Maret"
            $p2 = trim($parts[1]); // "11 Maret 2026" or "15 Mei 2026"

            // If p1 is just digits (09), it shares month/year with p2.
            // If p1 has text (30 Maret), it has its own month.

            // Extract from P2 (always has year)
            // Pattern: Day Month Year
            if (preg_match('/(\d+)\s+([A-Za-z]+)\s+(20\d\d)/', $p2, $m2)) {
                $d2 = $m2[1];
                $mo2 = $months[$m2[2]] ?? '01';
                $y2 = $m2[3];
                $end = "$y2-$mo2-$d2";

                // Check P1
                if (is_numeric($p1)) {
                    // Same month/year
                    $start = "$y2-$mo2-" . str_pad($p1, 2, '0', STR_PAD_LEFT);
                } else {
                    // Different month? "30 Maret"
                    if (preg_match('/(\d+)\s+([A-Za-z]+)/', $p1, $m1)) {
                        $d1 = $m1[1];
                        $mo1 = $months[$m1[2]] ?? '01';
                        $start = "$y2-$mo1-" . str_pad($d1, 2, '0', STR_PAD_LEFT);
                    } else {
                        return null;
                    }
                }
                return ['start' => $start, 'end' => $end];
            }
        } else {
            // Single date: "30 Maret 2026"
            if (preg_match('/(\d+)\s+([A-Za-z]+)\s+(20\d\d)/', $str, $m)) {
                $d = $m[1];
                $mo = $months[$m[2]] ?? '01';
                $y = $m[3];
                $date = "$y-$mo-" . str_pad($d, 2, '0', STR_PAD_LEFT);
                return ['start' => $date, 'end' => $date];
            }
        }
        return null;
    }





}
