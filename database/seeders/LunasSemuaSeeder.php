<?php

namespace Database\Seeders;

use App\Models\Invoice;
use App\Models\Mahasiswa;
use App\Models\Payment;
use App\Models\PaymentProof;
use App\Models\Pembayaran;
use App\Models\Semester;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class LunasSemuaSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Find the admin user
        $adminUser = User::where('email', 'admin@stih.ac.id')->first();
        if (!$adminUser) {
            $this->command->error('❌ Admin user admin@stih.ac.id not found. Run DatabaseSeeder first!');
            return;
        }

        // 2. Find student (Andi Pratama)
        $mahasiswa = Mahasiswa::where('nim', '2024010001')->first();
        if (!$mahasiswa) {
            $this->command->error('❌ Mahasiswa Andi Pratama (NIM 2024010001) not found. Run DatabaseSeeder first!');
            return;
        }

        $this->command->info("Seeding fully paid invoices for: {$mahasiswa->nama} (NIM: {$mahasiswa->nim})");

        // Insert into students table to satisfy the invoices table foreign key constraint
        \DB::table('students')->updateOrInsert(
            ['id' => $mahasiswa->id],
            [
                'user_id' => $mahasiswa->user_id,
                'npm' => $mahasiswa->nim,
                'nama' => $mahasiswa->nama,
                'prodi' => $mahasiswa->prodi ?? '-',
                'angkatan' => $mahasiswa->angkatan ?? '2024',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // 3. Clear existing payments & invoices for this student to avoid foreign key issues
        Payment::whereIn('invoice_id', function ($query) use ($mahasiswa) {
            $query->select('id')->from('invoices')->where('student_id', $mahasiswa->id);
        })->delete();

        PaymentProof::whereIn('invoice_id', function ($query) use ($mahasiswa) {
            $query->select('id')->from('invoices')->where('student_id', $mahasiswa->id);
        })->delete();

        Invoice::where('student_id', $mahasiswa->id)->delete();
        Pembayaran::where('mahasiswa_id', $mahasiswa->id)->delete();

        // 4. Create dummy invoices for Semesters 1 and 2 (LUNAS)
        $invoicesData = [
            [
                'semester' => 1,
                'tahun_ajaran' => '2024/2025',
                'sks_ambil' => 18,
                'paket_sks_bayar' => 18,
                'total_tagihan' => 4500000,
                'published_at' => Carbon::create(2024, 9, 1, 8, 0, 0),
            ],
            [
                'semester' => 2,
                'tahun_ajaran' => '2024/2025',
                'sks_ambil' => 20,
                'paket_sks_bayar' => 20,
                'total_tagihan' => 5000000,
                'published_at' => Carbon::create(2025, 2, 1, 8, 0, 0),
            ],
        ];

        foreach ($invoicesData as $data) {
            $invoice = Invoice::create([
                'student_id' => $mahasiswa->id,
                'semester' => $data['semester'],
                'tahun_ajaran' => $data['tahun_ajaran'],
                'sks_ambil' => $data['sks_ambil'],
                'paket_sks_bayar' => $data['paket_sks_bayar'],
                'total_tagihan' => $data['total_tagihan'],
                'status' => 'LUNAS',
                'allow_partial' => true,
                'notes' => 'Pembayaran uang kuliah Semester ' . $data['semester'],
                'created_by' => $adminUser->id,
                'published_at' => $data['published_at'],
            ]);

            // Create PaymentProof
            $proof = PaymentProof::create([
                'invoice_id' => $invoice->id,
                'installment_id' => null,
                'uploaded_by' => $mahasiswa->user_id,
                'transfer_date' => $data['published_at']->copy()->addDays(4),
                'amount_submitted' => $data['total_tagihan'],
                'method' => 'Transfer Bank',
                'file_path' => 'proofs/dummy_proof.jpg',
                'status' => 'APPROVED',
                'approved_by' => $adminUser->id,
                'approved_at' => $data['published_at']->copy()->addDays(5),
                'student_notes' => 'Lunas untuk semester ' . $data['semester'],
            ]);

            // Create Payment record
            Payment::create([
                'invoice_id' => $invoice->id,
                'installment_id' => null,
                'proof_id' => $proof->id,
                'amount_approved' => $data['total_tagihan'],
                'paid_date' => $data['published_at']->copy()->addDays(5),
                'transfer_date' => $data['published_at']->copy()->addDays(4),
                'approved_by' => $adminUser->id,
            ]);

            // 5. Seed legacy system Pembayaran if Semesters exist
            $semesterName = ($data['semester'] % 2 === 1) ? 'Ganjil' : 'Genap';
            $dbSemester = Semester::where('nama_semester', $semesterName)
                ->where('tahun_ajaran', $data['tahun_ajaran'])
                ->first();

            if ($dbSemester) {
                Pembayaran::create([
                    'mahasiswa_id' => $mahasiswa->id,
                    'semester_id' => $dbSemester->id,
                    'jenis' => 'SPP',
                    'jumlah' => $data['total_tagihan'],
                    'dibayar' => $data['total_tagihan'],
                    'status' => 'lunas',
                    'tanggal_bayar' => $data['published_at']->copy()->addDays(5),
                    'bukti_bayar' => 'proofs/dummy_proof.jpg',
                    'keterangan' => 'Lunas SPP Semester ' . $data['semester'] . ' (Legacy Data)',
                ]);
            }
        }

        $this->command->info("✅ Successfully created dummy payment data (LUNAS SEMUA) for Andi Pratama!");
    }
}
