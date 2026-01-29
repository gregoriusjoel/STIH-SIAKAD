<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Semester;

class PembayaranController extends Controller
{
    public function index()
    {
        $mahasiswa = Auth::user()->mahasiswa;

        // Dummy Data for Table 1: Pembayaran Kuliah (Semesters 1-8)
        $riwayatKuliah = [
            [
                'semester' => 1,
                'tagihan' => 12125000,
                'total_bayar' => 12125000,
                'tanggal_1' => '27-07-2021',
                'bayar_1' => 12125000,
                'tanggal_2' => '-',
                'bayar_2' => 0,
                'paket_sks' => 18,
                'sks_ambil' => 20,
                'ket' => 'Lunas'
            ],
            [
                'semester' => 2,
                'tagihan' => 12055000,
                'total_bayar' => 12055000,
                'tanggal_1' => '04-03-2022',
                'bayar_1' => 12055000,
                'tanggal_2' => '-',
                'bayar_2' => 0,
                'paket_sks' => 18,
                'sks_ambil' => 20,
                'ket' => 'Lunas'
            ],
            [
                'semester' => 3,
                'tagihan' => 12235000,
                'total_bayar' => 12235000,
                'tanggal_1' => '09-08-2022',
                'bayar_1' => 12235000,
                'tanggal_2' => '-',
                'bayar_2' => 0,
                'paket_sks' => 18,
                'sks_ambil' => 23,
                'ket' => 'Lunas'
            ],
            [
                'semester' => 4,
                'tagihan' => 13110000,
                'total_bayar' => 13110000,
                'tanggal_1' => '07-02-2023',
                'bayar_1' => 13110000,
                'tanggal_2' => '-',
                'bayar_2' => 0,
                'paket_sks' => 18,
                'sks_ambil' => 21,
                'ket' => 'Lunas'
            ],
            [
                'semester' => 5,
                'tagihan' => 12410000,
                'total_bayar' => 12410000,
                'tanggal_1' => '31-07-2023',
                'bayar_1' => 12410000,
                'tanggal_2' => '-',
                'bayar_2' => 0,
                'paket_sks' => 18,
                'sks_ambil' => 21,
                'ket' => 'Lunas'
            ],
            [
                'semester' => 6,
                'tagihan' => 12910000,
                'total_bayar' => 12910000,
                'tanggal_1' => '06-01-2024',
                'bayar_1' => 12910000,
                'tanggal_2' => '-',
                'bayar_2' => 0,
                'paket_sks' => 18,
                'sks_ambil' => 21,
                'ket' => 'Lunas'
            ],
            [
                'semester' => 7,
                'tagihan' => 8650000,
                'total_bayar' => 8650000,
                'tanggal_1' => '12-07-2024',
                'bayar_1' => 8650000,
                'tanggal_2' => '-',
                'bayar_2' => 0,
                'paket_sks' => 18,
                'sks_ambil' => 21,
                'ket' => 'Lunas'
            ],
            [
                'semester' => 8,
                'tagihan' => 6850000,
                'total_bayar' => 6850000,
                'tanggal_1' => '12-02-2025',
                'bayar_1' => 6850000,
                'tanggal_2' => '-',
                'bayar_2' => 0,
                'paket_sks' => 10,
                'sks_ambil' => 17,
                'ket' => 'Lunas'
            ],
        ];

        // Dummy Data for Table 2: Pembayaran Lainnya e.g., Wisuda, Sumbangan
        $riwayatLainnya = [
            [
                'tanggal' => '30-09-2025 05:00:00',
                'tagihan' => 1750000,
                'bayar' => 1750000,
                'jenis' => 'UANG PEMBAYARAN WISUDA DAN IJAZAH'
            ],
            [
                'tanggal' => '22-08-2025 17:23:00',
                'tagihan' => 100000,
                'bayar' => 100000,
                'jenis' => 'UANG PEMBAYARAN SUMBANGAN BUKU PERPUSTAKAAN'
            ],
            [
                'tanggal' => '16-11-2024 08:55:54',
                'tagihan' => 50000,
                'bayar' => 50000,
                'jenis' => 'Denda Kewajiban Pembayaran' // Keeping generic context fitting "Lainnya"
            ]
        ];

        return view('page.mahasiswa.pembayaran.index', compact(
            'mahasiswa',
            'riwayatKuliah',
            'riwayatLainnya'
        ));
    }
}
