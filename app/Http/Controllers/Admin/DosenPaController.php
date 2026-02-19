<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\Prodi;
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

        // Auto-fix kuota for dosens whose student count exceeds stored kuota
        foreach ($dosens as $dosen) {
            $currentKuota = $dosen->kuota ?: 6;
            if ($dosen->mahasiswa_pa_count > $currentKuota) {
                $dosen->kuota = $dosen->mahasiswa_pa_count;
                $dosen->save();
            }
        }

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

        // Auto-fix kuota for dosens whose student count exceeds stored kuota
        foreach ($dosens as $dosen) {
            $currentKuota = $dosen->kuota ?: 6;
            if ($dosen->mahasiswa_pa_count > $currentKuota) {
                $dosen->kuota = $dosen->mahasiswa_pa_count;
                $dosen->save();
            }
        }

        // Get Mahasiswa who don't have a Dosen PA yet
        $mahasiswas = Mahasiswa::with('user')
            ->whereDoesntHave('dosenPa')
            ->orderBy('id', 'asc')
            ->get();

        // Build prodi code-to-name mapping so JS can resolve dosen prodi codes to mahasiswa prodi names
        $prodiMap = Prodi::pluck('nama_prodi', 'kode_prodi')->toArray();

        return view('admin.dosen-pa.create', compact('dosens', 'mahasiswas', 'prodiMap'));
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

        // Save custom quota to database
        $quota = (int) $request->input('custom_quota', 6);
        if ($dosen->kuota != $quota) {
            $dosen->kuota = $quota;
            $dosen->save();
        }

        $currentCount = $dosen->mahasiswaPa()->count();
        $toAdd = count($mahasiswaIds);

        // Enforce quota limit per dosen
        if ($currentCount + $toAdd > $quota) {
            return back()->with('error', 'Dosen ini tidak dapat menampung ' . $toAdd . ' mahasiswa. Slot tersedia: ' . ($quota - $currentCount));
        }

        // Verify none of the selected mahasiswa already have a Dosen PA
        // and ensure they belong to the same prodi as the selected dosen
        $dosenProdiCodes = $dosen->prodi ?? [];
        if (!is_array($dosenProdiCodes)) $dosenProdiCodes = json_decode($dosenProdiCodes, true) ?: [];

        // Resolve prodi codes to names for comparison with mahasiswa prodi
        $prodiMap = Prodi::pluck('nama_prodi', 'kode_prodi')->toArray();
        $dosenProdiNames = array_filter(array_map(fn($code) => $prodiMap[$code] ?? null, $dosenProdiCodes));

        foreach ($mahasiswaIds as $mid) {
            $m = Mahasiswa::find($mid);
            if (!$m) continue;
            if ($m->dosenPa()->count() > 0) {
                return back()->with('error', 'Mahasiswa ' . $m->user->name . ' sudah memiliki Dosen PA. Pilih mahasiswa lain.');
            }
            // check program studi (compare mahasiswa prodi name with resolved dosen prodi names)
            $mProdi = $m->prodi ?? null;
            if ($mProdi && !empty($dosenProdiNames) && !in_array($mProdi, $dosenProdiNames)) {
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
        // Filter by same prodi as current dosen
        $currentProdi = $dosen->prodi ?? [];
        if (!is_array($currentProdi)) $currentProdi = json_decode($currentProdi, true) ?: [];

        $allDosens = Dosen::with('user')
            ->withCount('mahasiswaPa')
            ->where('id', '!=', $id) // Exclude current dosen
            ->orderBy('mahasiswa_pa_count', 'asc')
            ->get()
            ->filter(function($d) use ($currentProdi) {
                // If current dosen has no prodi, allow all? Or strictly none?
                // Assuming strict: only show dosens that share at least one prodi
                if (empty($currentProdi)) return true; // Fallback: if source has no prodi, show all (or maybe none?) - safest is show all or let validation handle it.
                
                $dProdi = $d->prodi ?? [];
                if (!is_array($dProdi)) $dProdi = json_decode($dProdi, true) ?: [];
                
                return !empty(array_intersect($currentProdi, $dProdi));
            });

        // Get Mahasiswa who don't have a Dosen PA yet (for swap/add feature)
        $query = Mahasiswa::with('user')->whereDoesntHave('dosenPa');

        // Restrict available mahasiswa to the same prodi as this dosen
        // Restrict available mahasiswa to the same prodi as this dosen
        $dosenProdiCodes = $dosen->prodi ?? [];
        if (!is_array($dosenProdiCodes)) $dosenProdiCodes = json_decode($dosenProdiCodes, true) ?: [];

        // Resolve prodi codes to names
        $prodiMap = Prodi::pluck('nama_prodi', 'kode_prodi')->toArray();
        $dosenProdiNames = array_filter(array_map(fn($code) => $prodiMap[$code] ?? null, $dosenProdiCodes));

        if (!empty($dosenProdiNames)) {
            $query->whereIn('prodi', $dosenProdiNames);
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
            $dosenProdiCodes = $currentDosen->prodi ?? [];
            if (!is_array($dosenProdiCodes)) $dosenProdiCodes = json_decode($dosenProdiCodes, true) ?: [];
            
            // Resolve prodi codes to names
            $prodiMap = Prodi::pluck('nama_prodi', 'kode_prodi')->toArray();
            $dosenProdiNames = array_filter(array_map(fn($code) => $prodiMap[$code] ?? null, $dosenProdiCodes));

            $mProdi = $addMahasiswa->prodi ?? null;
            if ($mProdi && !empty($dosenProdiNames) && !in_array($mProdi, $dosenProdiNames)) {
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

            $limit = $currentDosen->kuota ?: 6;
            if ($currentCount + $toAdd > $limit) {
                return back()->with('error', 'Dosen ini tidak dapat menampung ' . $toAdd . ' mahasiswa. Slot tersedia: ' . ($limit - $currentCount));
            }

            // Ensure none of the selected mahasiswa already have a Dosen PA
            $conflicts = Mahasiswa::whereIn('id', $toAddIds)->whereHas('dosenPa')->pluck('id')->all();
            if (!empty($conflicts)) {
                $m = Mahasiswa::find($conflicts[0]);
                return back()->with('error', 'Mahasiswa ' . ($m?->user->name ?? $conflicts[0]) . ' sudah memiliki Dosen PA. Pilih mahasiswa lain.');
            }

            // ensure all selected mahasiswa are from same prodi as current dosen
            $dosenProdiCodes = $currentDosen->prodi ?? [];
            if (!is_array($dosenProdiCodes)) $dosenProdiCodes = json_decode($dosenProdiCodes, true) ?: [];

            // Resolve prodi codes to names
            $prodiMap = Prodi::pluck('nama_prodi', 'kode_prodi')->toArray();
            $dosenProdiNames = array_filter(array_map(fn($code) => $prodiMap[$code] ?? null, $dosenProdiCodes));

            if (!empty($dosenProdiNames)) {
                // Check if any selected mahasiswa has a prodi NOT in the dosen's prodi names
                $bad = Mahasiswa::whereIn('id', $toAddIds)->whereNotIn('prodi', $dosenProdiNames)->pluck('id')->first();
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
        
        $limit = $newDosen->kuota ?: 6;
        if ($currentCount + $toTransfer > $limit) {
            return back()->with('error', 'Dosen tujuan tidak dapat menampung ' . $toTransfer . ' mahasiswa. Slot tersedia: ' . ($limit - $currentCount));
        }

        // ensure all mahasiswa to transfer are in same prodi as new dosen
        $newProdiCodes = $newDosen->prodi ?? [];
        if (!is_array($newProdiCodes)) $newProdiCodes = json_decode($newProdiCodes, true) ?: [];

        // Resolve prodi codes to names
        $prodiMap = Prodi::pluck('nama_prodi', 'kode_prodi')->toArray();
        $newProdiNames = array_filter(array_map(fn($code) => $prodiMap[$code] ?? null, $newProdiCodes));

        if (!empty($newProdiNames)) {
            $bad = Mahasiswa::whereIn('id', $request->mahasiswa_ids)->whereNotIn('prodi', $newProdiNames)->pluck('id')->first();
            if ($bad) {
                $m = Mahasiswa::find($bad);
                return back()->with('error', 'Mahasiswa ' . ($m?->user->name ?? $bad) . ' tidak berada di Program Studi yang sama dengan dosen tujuan. (Dosen: ' . implode(', ', $newProdiNames) . ')');
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

        // Reset kuota back to default
        $dosen->kuota = 6;
        $dosen->save();

        return redirect()->route('admin.dosen-pa.index')
            ->with('success', $count . ' mahasiswa berhasil dihapus dari Dosen PA ' . $dosen->user->name);
    }
}
