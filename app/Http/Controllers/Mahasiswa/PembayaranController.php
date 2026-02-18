<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Invoice;
use App\Models\Pembayaran;

class PembayaranController extends Controller
{
    public function index()
    {
        $student = Auth::user()->mahasiswa;
        
        // Get new system invoices
        $invoices = Invoice::where('student_id', $student->id)
            ->with(['installments', 'paymentProofs'])
            ->latest()
            ->get();
        
        // Get existing payment data from old system
        $existingPayments = Pembayaran::where('mahasiswa_id', $student->id)
            ->with('semester')
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Check for upcoming payments (H-7)
        $upcomingPayment = null;
        foreach ($invoices as $invoice) {
            if ($invoice->status === 'LUNAS') continue;

            foreach ($invoice->installments as $inst) {
                if ($inst->status === 'UNPAID' && $inst->due_date) {
                    $diff = now()->diffInDays($inst->due_date, false);
                    // Alert if due within 0-7 days
                    if ($diff >= 0 && $diff <= 7) {
                        $upcomingPayment = $inst;
                        break 2;
                    }
                }
            }
        }
        
        return view('page.mahasiswa.pembayaran.invoices.index', compact('invoices', 'existingPayments', 'upcomingPayment'));
    }
}
