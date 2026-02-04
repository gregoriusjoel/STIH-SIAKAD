<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JadwalGenerateLog;
use Illuminate\Http\Request;

class JadwalGenerateLogController extends Controller
{
    public function index(Request $request)
    {
        $logs = JadwalGenerateLog::with('user')
            ->latest()
            ->paginate(15);

        return view('admin.jadwal.generate-logs', compact('logs'));
    }

    public function show(JadwalGenerateLog $log)
    {
        return view('admin.jadwal.generate-log-detail', compact('log'));
    }

    public function export(JadwalGenerateLog $log)
    {
        if (!$log->failed_items || empty($log->failed_items)) {
            return back()->with('error', 'Tidak ada data gagal untuk di-export');
        }

        $csv = "No,Item Gagal\n";
        foreach ($log->failed_items as $index => $item) {
            $csv .= ($index + 1) . ',"' . str_replace('"', '""', $item) . "\"\n";
        }

        return response()->streamDownload(function () use ($csv) {
            echo $csv;
        }, 'jadwal-generate-failed-' . $log->created_at->format('Y-m-d-H-i-s') . '.csv', [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="jadwal-generate-failed-' . $log->created_at->format('Y-m-d-H-i-s') . '.csv"',
        ]);
    }
}
