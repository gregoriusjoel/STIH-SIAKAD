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
            'mahasiswa_ids' => 'required|array',
            'mahasiswa_ids.*' => 'exists:mahasiswas,id',
        ]);

        $dosen = Dosen::findOrFail($request->dosen_id);
        $mahasiswaIds = $request->input('mahasiswa_ids', []);

        $currentCount = $dosen->mahasiswaPa()->count();
        $toAdd = count($mahasiswaIds);

        // Enforce 6-student limit per dosen
        if ($currentCount + $toAdd > 6) {
            return back()->with('error', 'Dosen ini tidak dapat menampung ' . $toAdd . ' mahasiswa. Slot tersedia: ' . (6 - $currentCount));
        }

        // Verify none of the selected mahasiswa already have a Dosen PA
        // and ensure they belong to the same prodi as the selected dosen
        $dosenProdi = $dosen->prodi ?? [];
        if (!is_array($dosenProdi)) $dosenProdi = json_decode($dosenProdi, true) ?: [];

        if (empty($dosenProdi)) {
            return back()->with('error', 'Dosen belum memiliki Program Studi. Tambahkan prodi pada data dosen terlebih dahulu.');
        }

        foreach ($mahasiswaIds as $mid) {
            $m = Mahasiswa::find($mid);
            if (!$m) continue;
            if ($m->dosenPa()->count() > 0) {
                return back()->with('error', 'Mahasiswa ' . $m->user->name . ' sudah memiliki Dosen PA. Pilih mahasiswa lain.');
            }
            // check program studi
            $mProdi = $m->prodi ?? null;
            if ($mProdi && !in_array($mProdi, $dosenProdi)) {
                return back()->with('error', 'Mahasiswa ' . $m->user->name . ' tidak berada di Program Studi yang sama dengan dosen.');
            }
        }

        // Attach all selected mahasiswa
        $dosen->mahasiswaPa()->attach($mahasiswaIds);

        return redirect()->route('admin.dosen-pa.index')
            ->with('success', 'Dosen PA berhasil ditambahkan untuk ' . count($mahasiswaIds) . ' mahasiswa');
    }

    /**
     * Show form to edit Dosen PA assignment
     */
    public function edit(Request $request, $id)
    {
        $dosen = Dosen::with(['user', 'mahasiswaPa.user'])->findOrFail($id);

        // Get all Dosens with their mahasiswa count (for reassignment)
        $allDosens = Dosen::with('user')
            ->withCount('mahasiswaPa')
            ->where('id', '!=', $id) // Exclude current dosen
            ->orderBy('mahasiswa_pa_count', 'asc')
            ->get();

        // Get Mahasiswa who don't have a Dosen PA yet (for swap/add feature)
        $query = Mahasiswa::with('user')->whereDoesntHave('dosenPa');

        // Restrict available mahasiswa to the same prodi as this dosen
        $dosenProdi = $dosen->prodi ?? [];
        if (!is_array($dosenProdi)) $dosenProdi = json_decode($dosenProdi, true) ?: [];
        if (!empty($dosenProdi)) {
            $query->whereIn('prodi', $dosenProdi);
        } else {
            // if dosen has no prodi set, return empty set
            $query->whereRaw('0 = 1');
        }

        // search by name or nim
        $search = $request->query('search');
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nim', 'like', "%{$search}%")
                  ->orWhereHas('user', function($u) use ($search) {
                      $u->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $availableMahasiswas = $query->orderBy('id', 'asc')->paginate(5);

        // If AJAX requested, return rendered partial HTML
        if ($request->ajax()) {
            $html = view('admin.dosen-pa._available_list', compact('availableMahasiswas'))->render();
            return response()->json(['html' => $html]);
        }

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

            // ensure prodi matches current dosen
            $dosenProdi = $currentDosen->prodi ?? [];
            if (!is_array($dosenProdi)) $dosenProdi = json_decode($dosenProdi, true) ?: [];
            $mProdi = $addMahasiswa->prodi ?? null;
            if ($mProdi && !empty($dosenProdi) && !in_array($mProdi, $dosenProdi)) {
                return back()->with('error', 'Mahasiswa ' . $addMahasiswa->user->name . ' tidak berada di Program Studi yang sama dengan dosen ini.');
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

        if ($action === 'add') {
            $request->validate([
                'mahasiswa_ids' => 'required|array',
                'mahasiswa_ids.*' => 'exists:mahasiswas,id',
            ]);

            $toAddIds = $request->input('mahasiswa_ids', []);
            $currentCount = $currentDosen->mahasiswaPa()->count();
            $toAdd = count($toAddIds);

            if ($currentCount + $toAdd > 6) {
                return back()->with('error', 'Dosen ini tidak dapat menampung ' . $toAdd . ' mahasiswa. Slot tersedia: ' . (6 - $currentCount));
            }

            // Ensure none of the selected mahasiswa already have a Dosen PA
            $conflicts = Mahasiswa::whereIn('id', $toAddIds)->whereHas('dosenPa')->pluck('id')->all();
            if (!empty($conflicts)) {
                $m = Mahasiswa::find($conflicts[0]);
                return back()->with('error', 'Mahasiswa ' . ($m?->user->name ?? $conflicts[0]) . ' sudah memiliki Dosen PA. Pilih mahasiswa lain.');
            }

            // ensure all selected mahasiswa are from same prodi as current dosen
            $dosenProdi = $currentDosen->prodi ?? [];
            if (!is_array($dosenProdi)) $dosenProdi = json_decode($dosenProdi, true) ?: [];
            if (!empty($dosenProdi)) {
                $bad = Mahasiswa::whereIn('id', $toAddIds)->whereNotIn('prodi', $dosenProdi)->pluck('id')->first();
                if ($bad) {
                    $m = Mahasiswa::find($bad);
                    return back()->with('error', 'Mahasiswa ' . ($m?->user->name ?? $bad) . ' tidak berada di Program Studi yang sama dengan dosen ini.');
                }
            } else {
                return back()->with('error', 'Dosen belum memiliki Program Studi. Tambahkan prodi pada data dosen terlebih dahulu.');
            }

            // Attach
            $currentDosen->mahasiswaPa()->attach($toAddIds);

            return redirect()->route('admin.dosen-pa.edit', $currentDosen->id)
                ->with('success', count($toAddIds) . ' mahasiswa berhasil ditambahkan ke ' . $currentDosen->user->name);
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
        
        if ($currentCount + $toTransfer > 6) {
            return back()->with('error', 'Dosen tujuan tidak dapat menampung ' . $toTransfer . ' mahasiswa. Slot tersedia: ' . (6 - $currentCount));
        }

        // ensure all mahasiswa to transfer are in same prodi as new dosen
        $newProdi = $newDosen->prodi ?? [];
        if (!is_array($newProdi)) $newProdi = json_decode($newProdi, true) ?: [];
        if (!empty($newProdi)) {
            $bad = Mahasiswa::whereIn('id', $request->mahasiswa_ids)->whereNotIn('prodi', $newProdi)->pluck('id')->first();
            if ($bad) {
                $m = Mahasiswa::find($bad);
                return back()->with('error', 'Mahasiswa ' . ($m?->user->name ?? $bad) . ' tidak berada di Program Studi yang sama dengan dosen tujuan.');
            }
        } else {
            return back()->with('error', 'Dosen tujuan belum memiliki Program Studi.');
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
                'nim' => $mahasiswa->nim,
                'program_studi' => $mahasiswa->prodi ?? 'Ilmu Hukum',
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
