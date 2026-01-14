<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        if (auth()->user()->role === 'dosen') {
            return redirect()->route('dosen.dashboard');
        }

        return view('dashboard.index');
    }
}
