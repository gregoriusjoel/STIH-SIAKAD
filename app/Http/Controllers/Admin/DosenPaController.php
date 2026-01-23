<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DosenPaController extends Controller
{
    /**
     * Display list of Dosen PA with mahasiswa count
     */
    public function index()
    {
        // Get Dosens who have at least 1 mahasiswa PA, with count
        $dosens = Dosen::with('user')
            ->withCount('mahasiswaPa')
            ->having('mahasiswa_pa_count', '>', 0)
            ->orderBy('mahasiswa_pa_count', 'desc')
            ->paginate(10);

        return view('admin.dosen-pa.index', compact('dosens'));
    }

    /**
     * Show form to create new Dosen PA assignment
     */
    public function create()
    {
        // Get all Dosens with their mahasiswa count
        $dosens = Dosen::with('user')
            ->withCount('mahasiswaPa')
            ->orderBy('mahasiswa_pa_count', 'asc')
            ->get();

        // Get Mahasiswa who don't have a Dosen PA yet
        $mahasiswas = Mahasiswa::with('user')
            ->whereDoesntHave('dosenPa')
            ->orderBy('id', 'asc')
            ->get();

        return view('admin.dosen-pa.create', compact('dosens', 'mahasiswas'));
    }

    /**
     * Store new Dosen PA assignment
     */
    public function store(Request $request)
    {
        $request->validate([
            'dosen_id' => 'required|exists:dosens,id',
            'mahasiswa_id' => 'required|exists:mahasiswas,id',
        ]);

        $dosen = Dosen::findOrFail($request->dosen_id);
        $mahasiswa = Mahasiswa::findOrFail($request->mahasiswa_id);

        // Check if dosen already has 10 mahasiswa
        if ($dosen->mahasiswaPa()->count() >= 10) {
            return back()->with('error', 'Dosen ini sudah mencapai batas maksimal 10 mahasiswa.');
        }

        // Check if mahasiswa already has a Dosen PA
        if ($mahasiswa->dosenPa()->count() > 0) {
            return back()->with('error', 'Mahasiswa ini sudah memiliki Dosen PA.');
        }

        // Create the relationship
        $dosen->mahasiswaPa()->attach($mahasiswa->id);

        return redirect()->route('admin.dosen-pa.index')
            ->with('success', 'Dosen PA berhasil ditambahkan untuk ' . $mahasiswa->user->name);
    }

    /**
     * Show form to edit Dosen PA assignment
     */
    public function edit($id)
    {
        $dosen = Dosen::with(['user', 'mahasiswaPa.user'])->findOrFail($id);
        
        // Get all Dosens with their mahasiswa count (for reassignment)
        $allDosens = Dosen::with('user')
            ->withCount('mahasiswaPa')
            ->where('id', '!=', $id) // Exclude current dosen
            ->orderBy('mahasiswa_pa_count', 'asc')
            ->get();

        // Get Mahasiswa who don't have a Dosen PA yet (for swap feature)
        $availableMahasiswas = Mahasiswa::with('user')
            ->whereDoesntHave('dosenPa')
            ->orderBy('id', 'asc')
            ->get();

        return view('admin.dosen-pa.edit', compact('dosen', 'allDosens', 'availableMahasiswas'));
    }

    /**
     * Update Dosen PA assignment (reassign or swap mahasiswa)
     */
    public function update(Request $request, $id)
    {
        $action = $request->input('action', 'transfer');
        $currentDosen = Dosen::with('user')->findOrFail($id);

        if ($action === 'swap') {
            // Swap: Remove one mahasiswa and add another
            $request->validate([
                'remove_mahasiswa_id' => 'required|exists:mahasiswas,id',
                'add_mahasiswa_id' => 'required|exists:mahasiswas,id|different:remove_mahasiswa_id',
            ]);

            $removeMahasiswa = Mahasiswa::with('user')->findOrFail($request->remove_mahasiswa_id);
            $addMahasiswa = Mahasiswa::with('user')->findOrFail($request->add_mahasiswa_id);

            // Check if add_mahasiswa already has a Dosen PA
            if ($addMahasiswa->dosenPa()->count() > 0) {
                return back()->with('error', 'Mahasiswa ' . $addMahasiswa->user->name . ' sudah memiliki Dosen PA.');
            }

            // Check if remove_mahasiswa belongs to this dosen
            if (!$currentDosen->mahasiswaPa()->where('mahasiswas.id', $removeMahasiswa->id)->exists()) {
                return back()->with('error', 'Mahasiswa ' . $removeMahasiswa->user->name . ' bukan bimbingan dosen ini.');
            }

            // Detach remove and attach add
            $currentDosen->mahasiswaPa()->detach($removeMahasiswa->id);
            $currentDosen->mahasiswaPa()->attach($addMahasiswa->id);

            return redirect()->route('admin.dosen-pa.index')
                ->with('success', 'Mahasiswa berhasil diganti: ' . $removeMahasiswa->user->name . ' → ' . $addMahasiswa->user->name);
        }

        // Default action: Transfer to another dosen
        $request->validate([
            'mahasiswa_ids' => 'required|array',
            'mahasiswa_ids.*' => 'exists:mahasiswas,id',
            'new_dosen_id' => 'required|exists:dosens,id|different:current_dosen_id',
        ]);

        $newDosen = Dosen::with('user')->findOrFail($request->new_dosen_id);

        // Check if new dosen can accept these mahasiswa
        $currentCount = $newDosen->mahasiswaPa()->count();
        $toTransfer = count($request->mahasiswa_ids);
        
        if ($currentCount + $toTransfer > 10) {
            return back()->with('error', 'Dosen tujuan tidak dapat menampung ' . $toTransfer . ' mahasiswa. Slot tersedia: ' . (10 - $currentCount));
        }

        // Detach from current dosen and attach to new dosen
        $currentDosen->mahasiswaPa()->detach($request->mahasiswa_ids);
        $newDosen->mahasiswaPa()->attach($request->mahasiswa_ids);

        return redirect()->route('admin.dosen-pa.index')
            ->with('success', $toTransfer . ' mahasiswa berhasil dipindahkan ke ' . $newDosen->user->name);
    }

    /**
     * Get list of mahasiswa for a specific Dosen PA (JSON API)
     */
    public function getMahasiswa($id)
    {
        $dosen = Dosen::with(['mahasiswaPa.user'])->findOrFail($id);
        
        $mahasiswaList = $dosen->mahasiswaPa->map(function($mahasiswa) {
            return [
                'id' => $mahasiswa->id,
                'name' => $mahasiswa->user->name,
                'npm' => $mahasiswa->npm,
                'program_studi' => $mahasiswa->program_studi ?? 'Ilmu Hukum',
                'semester' => $mahasiswa->semester ?? 1,
            ];
        });

        return response()->json($mahasiswaList);
    }

    /**
     * Remove all Dosen PA assignments for a specific dosen
     */
    public function destroy($id)
    {
        $dosen = Dosen::with('user')->find($id);

        if (!$dosen) {
            return back()->with('error', 'Data dosen tidak ditemukan.');
        }

        $count = $dosen->mahasiswaPa()->count();
        
        if ($count == 0) {
            return back()->with('error', 'Dosen ini tidak memiliki mahasiswa PA.');
        }

        // Detach all mahasiswa from this dosen
        $dosen->mahasiswaPa()->detach();

        return redirect()->route('admin.dosen-pa.index')
            ->with('success', $count . ' mahasiswa berhasil dihapus dari Dosen PA ' . $dosen->user->name);
    }
}
