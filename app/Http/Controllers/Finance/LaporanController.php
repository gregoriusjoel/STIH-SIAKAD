<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\PaymentProof;
use App\Models\InstallmentRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $tahunAjaran = $request->get('tahun_ajaran');
        $semester    = $request->get('semester');
        $bulan       = $request->get('bulan');   // format: 2026-04
        $status      = $request->get('status');

        // ---------- Summary Stats ----------
        $invoiceQuery = Invoice::query();
        if ($tahunAjaran) $invoiceQuery->where('tahun_ajaran', $tahunAjaran);
        if ($semester)    $invoiceQuery->where('semester', $semester);

        $totalTagihan    = (clone $invoiceQuery)->where('status', '!=', 'CANCELLED')->sum('total_tagihan');
        $totalLunas      = (clone $invoiceQuery)->where('status', 'LUNAS')->sum('total_tagihan');
        $jumlahInvoice   = (clone $invoiceQuery)->where('status', '!=', 'CANCELLED')->count();
        $jumlahLunas     = (clone $invoiceQuery)->where('status', 'LUNAS')->count();
        $jumlahCicilan   = (clone $invoiceQuery)->where('status', 'IN_INSTALLMENT')->count();
        $jumlahBelumBayar= (clone $invoiceQuery)->where('status', 'PUBLISHED')->count();

        // Total terkumpul dari payments
        $paymentQuery = Payment::query()
            ->join('invoices', 'payments.invoice_id', '=', 'invoices.id')
            ->select('payments.*');
        if ($tahunAjaran) $paymentQuery->where('invoices.tahun_ajaran', $tahunAjaran);
        if ($semester)    $paymentQuery->where('invoices.semester', $semester);
        if ($bulan) {
            $paymentQuery->whereYear('payments.paid_date', Carbon::parse($bulan)->year)
                         ->whereMonth('payments.paid_date', Carbon::parse($bulan)->month);
        }
        $totalTerkumpul = (clone $paymentQuery)->sum('payments.amount_approved');

        // Sisa 
        $sisaTagihan = $totalTagihan - $totalLunas;

        // ---------- Monthly Trend (12 bulan terakhir) ----------
        $monthlyData = Payment::select(
                DB::raw("DATE_FORMAT(paid_date, '%Y-%m') as bulan_fmt"),
                DB::raw('SUM(amount_approved) as total')
            )
            ->join('invoices', 'payments.invoice_id', '=', 'invoices.id')
            ->when($tahunAjaran, fn($q) => $q->where('invoices.tahun_ajaran', $tahunAjaran))
            ->when($semester, fn($q) => $q->where('invoices.semester', $semester))
            ->where('payments.paid_date', '>=', now()->subMonths(11)->startOfMonth())
            ->groupBy('bulan_fmt')
            ->orderBy('bulan_fmt')
            ->get()
            ->keyBy('bulan_fmt');

        $trendLabels = [];
        $trendValues = [];
        for ($i = 11; $i >= 0; $i--) {
            $m   = now()->subMonths($i)->format('Y-m');
            $lbl = now()->subMonths($i)->locale('id')->isoFormat('MMM YY');
            $trendLabels[] = $lbl;
            $trendValues[] = $monthlyData->has($m) ? (int) $monthlyData[$m]->total : 0;
        }

        // ---------- Status Breakdown ----------
        $statusBreakdown = Invoice::select('status', DB::raw('count(*) as jumlah'), DB::raw('sum(total_tagihan) as nominal'))
            ->when($tahunAjaran, fn($q) => $q->where('tahun_ajaran', $tahunAjaran))
            ->when($semester, fn($q) => $q->where('semester', $semester))
            ->where('status', '!=', 'CANCELLED')
            ->groupBy('status')
            ->get();

        // ---------- Invoice List (filterable) ----------
        $invoices = Invoice::with(['student.user', 'payments'])
            ->when($tahunAjaran, fn($q) => $q->where('tahun_ajaran', $tahunAjaran))
            ->when($semester, fn($q) => $q->where('semester', $semester))
            ->when($status, fn($q) => $q->where('status', $status))
            ->when($bulan, function ($q) use ($bulan) {
                $q->whereHas('payments', function ($pq) use ($bulan) {
                    $pq->whereYear('paid_date', Carbon::parse($bulan)->year)
                       ->whereMonth('paid_date', Carbon::parse($bulan)->month);
                });
            })
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        // ---------- Filter Options ----------
        $tahunAjaranOptions = Invoice::select('tahun_ajaran')
            ->distinct()->orderByDesc('tahun_ajaran')->pluck('tahun_ajaran');

        return view('finance.laporan.index', compact(
            'totalTagihan', 'totalLunas', 'totalTerkumpul', 'sisaTagihan',
            'jumlahInvoice', 'jumlahLunas', 'jumlahCicilan', 'jumlahBelumBayar',
            'trendLabels', 'trendValues',
            'statusBreakdown',
            'invoices',
            'tahunAjaranOptions',
            'tahunAjaran', 'semester', 'bulan', 'status'
        ));
    }
}
