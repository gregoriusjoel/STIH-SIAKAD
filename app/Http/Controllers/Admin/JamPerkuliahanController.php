<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JamPerkuliahan;
use Illuminate\Http\Request;

class JamPerkuliahanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $jamPerkuliahan = JamPerkuliahan::orderBy('is_active', 'desc')->orderBy('jam_ke')->paginate(10);
        return view('admin.jam-perkuliahan.index', compact('jamPerkuliahan'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Get the last jam perkuliahan to suggest next start time
        $lastJam = JamPerkuliahan::orderBy('jam_ke', 'desc')->first();
        $suggestedJamKe = $lastJam ? $lastJam->jam_ke + 1 : 1;
        $suggestedJamMulai = $lastJam ? date('H:i', strtotime($lastJam->jam_selesai)) : '';
        
        return view('admin.jam-perkuliahan.create', compact('lastJam', 'suggestedJamKe', 'suggestedJamMulai'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'jam_ke' => 'required|integer|min:1|unique:jam_perkuliahan,jam_ke',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => [
                'required',
                'date_format:H:i',
                function ($attribute, $value, $fail) use ($request) {
                    $jamMulai = $request->input('jam_mulai');
                    if ($jamMulai && $value !== '00:00' && strtotime($value) <= strtotime($jamMulai)) {
                        $fail('Jam selesai harus lebih besar dari jam mulai (kecuali 00:00).');
                    }
                },
            ],
            'is_active' => 'boolean',
        ]);

        JamPerkuliahan::create([
            'jam_ke' => $request->jam_ke,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'is_active' => (bool) $request->input('is_active'),
        ]);

        return redirect()->route('admin.jam-perkuliahan.index')
            ->with('success', 'Jam perkuliahan berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $jam = JamPerkuliahan::findOrFail($id);
        return view('admin.jam-perkuliahan.edit', compact('jam'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $jam = JamPerkuliahan::findOrFail($id);

        $request->validate([
            'jam_ke' => 'required|integer|min:1|unique:jam_perkuliahan,jam_ke,' . $id,
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => [
                'required',
                'date_format:H:i',
                function ($attribute, $value, $fail) use ($request) {
                    $jamMulai = $request->input('jam_mulai');
                    if ($jamMulai && $value !== '00:00' && strtotime($value) <= strtotime($jamMulai)) {
                        $fail('Jam selesai harus lebih besar dari jam mulai (kecuali 00:00).');
                    }
                },
            ],
            'is_active' => 'boolean',
        ]);

        $jam->update([
            'jam_ke' => $request->jam_ke,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'is_active' => (bool) $request->input('is_active'),
        ]);

        return redirect()->route('admin.jam-perkuliahan.index')
            ->with('success', 'Jam perkuliahan berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $jam = JamPerkuliahan::findOrFail($id);
        $jam->delete();

        return redirect()->route('admin.jam-perkuliahan.index')
            ->with('success', 'Jam perkuliahan berhasil dihapus');
    }
}
