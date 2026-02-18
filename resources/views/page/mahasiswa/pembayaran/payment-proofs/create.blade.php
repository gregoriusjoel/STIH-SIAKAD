@extends('layouts.mahasiswa')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="mb-6">
            <a href="{{ route('mahasiswa.invoices.show', $invoice) }}" class="text-blue-600 hover:text-blue-800">
                ← Kembali
            </a>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h1 class="text-2xl font-bold text-gray-800 mb-6">Upload Bukti Pembayaran</h1>

            <!-- Installment Info -->
            <div class="bg-gray-50 rounded p-4 mb-6">
                <p class="text-sm text-gray-600 mb-1">Cicilan Ke-{{ $installment->installment_no }}</p>
                <p class="text-xs text-gray-500 mb-3">
                    Semester {{ $invoice->semester }} - {{ $invoice->tahun_ajaran }}
                </p>
                <p class="text-2xl font-bold text-gray-900">
                    Rp {{ number_format($installment->amount, 0, ',', '.') }}
                </p>
                @if($installment->due_date)
                    <p class="text-sm text-gray-600 mt-2">
                        Jatuh tempo: {{ $installment->due_date->format('d M Y') }}
                    </p>
                @endif
            </div>

            <form action="{{ route('mahasiswa.payment-proofs.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="installment_id" value="{{ $installment->id }}">

                <!-- Transfer Date -->
                <div class="mb-6">
                    <label for="transfer_date" class="block text-sm font-medium text-gray-700 mb-2">
                        Tanggal Transfer <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="transfer_date" id="transfer_date" 
                           max="{{ date('Y-m-d') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 @error('transfer_date') border-red-500 @enderror"
                           value="{{ old('transfer_date') }}" required>
                    @error('transfer_date')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Amount Submitted -->
                <div class="mb-6">
                    <label for="amount_submitted" class="block text-sm font-medium text-gray-700 mb-2">
                        Nominal yang Dibayar <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-4 top-2 text-gray-600">Rp</span>
                        <input type="number" name="amount_submitted" id="amount_submitted" 
                               class="w-full pl-12 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 @error('amount_submitted') border-red-500 @enderror"
                               value="{{ old('amount_submitted', $installment->amount) }}" 
                               required>
                    </div>
                    @error('amount_submitted')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                    @if(!$invoice->allow_partial)
                        <p class="mt-1 text-xs text-yellow-600">* Nominal harus sama dengan nominal cicilan</p>
                    @endif
                </div>

                <!-- Payment Method -->
                <div class="mb-6">
                    <label for="method" class="block text-sm font-medium text-gray-700 mb-2">
                        Metode Pembayaran (Opsional)
                    </label>
                    <select name="method" id="method" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Pilih metode...</option>
                        <option value="Transfer Bank" {{ old('method') == 'Transfer Bank' ? 'selected' : '' }}>Transfer Bank</option>
                        <option value="VA" {{ old('method') == 'VA' ? 'selected' : '' }}>Virtual Account</option>
                        <option value="E-Wallet" {{ old('method') == 'E-Wallet' ? 'selected' : '' }}>E-Wallet</option>
                        <option value="Tunai" {{ old('method') == 'Tunai' ? 'selected' : '' }}>Tunai</option>
                    </select>
                </div>

                <!-- File Upload -->
                <div class="mb-6">
                    <label for="file" class="block text-sm font-medium text-gray-700 mb-2">
                        Bukti Transfer (JPG, PNG, PDF) <span class="text-red-500">*</span>
                    </label>
                    <input type="file" name="file" id="file" accept=".jpg,.jpeg,.png,.pdf"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 @error('file') border-red-500 @enderror"
                           required>
                    @error('file')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">Maksimal 2MB. Format: JPG, JPEG, PNG, PDF</p>
                </div>

                <!-- Student Notes -->
                <div class="mb-6">
                    <label for="student_notes" class="block text-sm font-medium text-gray-700 mb-2">
                        Catatan (Opsional)
                    </label>
                    <textarea name="student_notes" id="student_notes" rows="3"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                              placeholder="Catatan tambahan jika ada...">{{ old('student_notes') }}</textarea>
                </div>

                <!-- Submit Buttons -->
                <div class="flex gap-4">
                    <button type="submit" 
                            class="flex-1 px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium">
                        Upload Bukti Bayar
                    </button>
                    <a href="{{ route('mahasiswa.invoices.show', $invoice) }}" 
                       class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 font-medium">
                        Batal
                    </a>
                </div>
            </form>
        </div>

        <!-- Info Box -->
        <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
            <h3 class="font-semibold text-blue-800 mb-2">Informasi Penting:</h3>
            <ul class="text-sm text-blue-700 space-y-1 list-disc list-inside">
                <li>Pastikan bukti transfer jelas dan dapat dibaca</li>
                <li>Nominal harus sesuai dengan tagihan cicilan</li>
                <li>Bukti bayar akan diverifikasi oleh Tim Keuangan</li>
                <li>Pembayaran dianggap sah setelah diverifikasi dan di-ACC</li>
                <li>Jika ditolak, Anda dapat upload ulang bukti transfer yang benar</li>
            </ul>
        </div>
    </div>
</div>
@endsection
