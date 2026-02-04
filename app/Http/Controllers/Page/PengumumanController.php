<?php

namespace App\Http\Controllers\Page;

use App\Http\Controllers\Controller;
use App\Models\Pengumuman;

class PengumumanController extends Controller
{
    public function index()
    {
        $query = Pengumuman::whereNotNull('published_at');

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
        return view('pengumuman.show', compact('pengumuman'));
    }
}
