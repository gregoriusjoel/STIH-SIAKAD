<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\KuesionerMahasiswaBaru;
use App\Models\SurveyQuestion;

class NewStudentSurveyController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $mahasiswa = $user->mahasiswa;

        $questions = SurveyQuestion::all();

        return view('page.mahasiswa.survey.index', compact('mahasiswa', 'questions'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $mahasiswa = $user->mahasiswa;
        // build validation rules from questions model
        $rules = [];
        $questions = SurveyQuestion::all();
        foreach ($questions as $q) {
            $rules[$q['key']] = 'required|in:4,3,2,1';
        }
        $rules['saran'] = 'required|string|max:2000';

        $validated = $request->validate($rules);

        // store answers dynamically
        $answers = [];
        foreach ($questions as $q) {
            $answers[$q['key']] = (int) ($validated[$q['key']] ?? 0);
        }
        $answers['saran'] = $validated['saran'] ?? null;

        // persist into dedicated columns (q1..q7) instead of JSON blob
        $payload = [
            'mahasiswa_id' => $mahasiswa->id,
            'saran' => $answers['saran'] ?? null,
        ];
        foreach (range(1,7) as $i) {
            $key = 'q' . $i;
            $payload[$key] = $answers[$key] ?? null;
        }

        // include automatic student meta
        $payload['email'] = $user->email ?? null;
        $payload['prodi'] = $mahasiswa->prodi ?? $mahasiswa->prodi ?? null;
        $payload['jenis_kelamin'] = $mahasiswa->jenis_kelamin ?? null;
        $payload['angkatan'] = is_numeric($mahasiswa->angkatan) ? (int) $mahasiswa->angkatan : null;

        KuesionerMahasiswaBaru::create($payload);

        // mark mahasiswa as completed
        $mahasiswa->new_survey_completed = true;
        $mahasiswa->save();

        return redirect()->route('mahasiswa.dashboard')->with('success', 'Terima kasih, kuesioner telah disimpan.');
    }
}
