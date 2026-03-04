<?php

namespace App\Http\Controllers\Page;

use App\Http\Controllers\Controller;
use App\Models\Pengumuman;

class PengumumanController extends Controller
{
    public function index()
    {
        $query = Pengumuman::where('published_at', '<=', now('Asia/Jakarta')->format('Y-m-d H:i:s'));

        if (auth()->check()) {
            $role = auth()->user()->role;
            if ($role === 'dosen') {
                $query->whereIn('target', ['semua', 'dosen']);
            } elseif ($role === 'mahasiswa') {
                $query->whereIn('target', ['semua', 'mahasiswa']);
            }
        }

        $pengumumans = $query->orderByDesc('published_at')->paginate(10);
        return view('pengumuman.index', compact('pengumumans'));
    }

    public function show(Pengumuman $pengumuman)
    {
        if ($pengumuman->published_at > now('Asia/Jakarta')->format('Y-m-d H:i:s') && (!auth()->check() || auth()->user()->role !== 'admin')) {
            abort(404);
        }
        return view('pengumuman.show', compact('pengumuman'));
    }
}
