<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\DosenAvailability;
use App\Models\JamPerkuliahan;
use App\Models\Semester;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DosenAvailabilityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $dosen = Auth::user()->dosen;
        $activeSemester = Semester::where('status', 'aktif')->first() 
            ?? Semester::where('is_active', true)->first();
        
        if (!$activeSemester) {
            return redirect()->back()->with('error', 'Tidak ada semester aktif.');
        }

        $availabilities = DosenAvailability::with('jamPerkuliahan')
            ->forDosen($dosen->id)
            ->forSemester($activeSemester->id)
            ->get()
            ->groupBy('hari');

        return view('dosen.availability.index', compact('availabilities', 'activeSemester'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $dosen = Auth::user()->dosen;
        $activeSemester = Semester::where('status', 'aktif')->first() 
            ?? Semester::where('is_active', true)->first();
        
        if (!$activeSemester) {
            return redirect()->back()->with('error', 'Tidak ada semester aktif.');
        }

        $jamPerkuliahan = JamPerkuliahan::where('is_active', true)
            ->orderBy('jam_ke')
            ->get();

        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

        // Get existing availabilities
        $existingAvailabilities = DosenAvailability::forDosen($dosen->id)
            ->forSemester($activeSemester->id)
            ->get()
            ->mapWithKeys(function ($item) {
                return ["{$item->hari}_{$item->jam_perkuliahan_id}" => $item];
            });

        return view('dosen.availability.create', compact(
            'jamPerkuliahan',
            'days',
            'activeSemester',
            'existingAvailabilities'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'semester_id' => 'required|exists:semesters,id',
            'slots' => 'required|array',
            'slots.*' => 'string', // Format: "hari_jam_perkuliahan_id"
        ]);

        $dosen = Auth::user()->dosen;

        DB::beginTransaction();
        try {
            // Delete existing availabilities for this semester
            DosenAvailability::forDosen($dosen->id)
                ->forSemester($request->semester_id)
                ->delete();

            // Insert new availabilities
            $availabilities = [];
            foreach ($request->slots as $slot) {
                [$hari, $jamPerkuliahanId] = explode('_', $slot);
                
                $availabilities[] = [
                    'dosen_id' => $dosen->id,
                    'semester_id' => $request->semester_id,
                    'hari' => $hari,
                    'jam_perkuliahan_id' => $jamPerkuliahanId,
                    'status' => 'available',
                    'notes' => $request->notes ?? null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            DosenAvailability::insert($availabilities);

            DB::commit();

            return redirect()->route('dosen.availability.index')
                ->with('success', 'Ketersediaan waktu berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $availability = DosenAvailability::findOrFail($id);
        
        // Check ownership
        if ($availability->dosen_id !== Auth::user()->dosen->id) {
            abort(403);
        }

        // Check if already booked
        if ($availability->status === 'booked') {
            return redirect()->back()
                ->with('error', 'Tidak dapat menghapus slot yang sudah dijadwalkan.');
        }

        $availability->delete();

        return redirect()->back()
            ->with('success', 'Slot ketersediaan berhasil dihapus.');
    }
}
