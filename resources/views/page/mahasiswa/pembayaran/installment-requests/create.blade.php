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
            <h1 class="text-2xl font-bold text-gray-800 mb-6">Ajukan Cicilan</h1>

            <!-- Invoice Info -->
            <div class="bg-gray-50 rounded p-4 mb-6">
                <p class="text-sm text-gray-600 mb-2">Tagihan:</p>
                <p class="text-lg font-semibold">Semester {{ $invoice->semester }} - {{ $invoice->tahun_ajaran }}</p>
                <p class="text-2xl font-bold text-gray-900 mt-2">
                    Rp {{ number_format($invoice->total_tagihan, 0, ',', '.') }}
                </p>
            </div>

            <form action="{{ route('mahasiswa.installment-requests.store', $invoice) }}" method="POST">
                @csrf

                <!-- Requested Terms -->
                <div class="mb-6">
                    <label for="requested_terms" class="block text-sm font-medium text-gray-700 mb-2">
                        Jumlah Cicilan yang Diinginkan <span class="text-red-500">*</span>
                    </label>
                    <select name="requested_terms" id="requested_terms" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 @error('requested_terms') border-red-500 @enderror"
                            required onchange="calculateInstallment()">
                        <option value="">Pilih jumlah cicilan</option>
                        @for($i = 2; $i <= 12; $i++)
                            <option value="{{ $i }}" {{ old('requested_terms') == $i ? 'selected' : '' }}>
                                {{ $i }}x Cicilan
                            </option>
                        @endfor
                    </select>
                    @error('requested_terms')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Preview Calculation -->
                <div id="calculation-preview" class="hidden mb-6 p-4 bg-blue-50 rounded-lg">
                    <p class="text-sm text-gray-700 mb-2 font-medium">Estimasi Cicilan per Bulan:</p>
                    <p class="text-xl font-bold text-blue-700" id="estimated-amount">-</p>
                    <p class="text-xs text-gray-600 mt-1">*Angka estimasi, cicilan terakhir bisa berbeda</p>
                </div>

                <!-- Alasan -->
                <div class="mb-6">
                    <label for="alasan" class="block text-sm font-medium text-gray-700 mb-2">
                        Alasan Pengajuan Cicilan <span class="text-red-500">*</span>
                    </label>
                    <textarea name="alasan" id="alasan" rows="4"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 @error('alasan') border-red-500 @enderror"
                              placeholder="Jelaskan alasan Anda mengajukan cicilan..."
                              required>{{ old('alasan') }}</textarea>
                    @error('alasan')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">Maksimal 500 karakter</p>
                </div>

                <!-- Submit Buttons -->
                <div class="flex gap-4">
                    <button type="submit" 
                            class="flex-1 px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium">
                        Ajukan Cicilan
                    </button>
                    <a href="{{ route('mahasiswa.invoices.show', $invoice) }}" 
                       class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 font-medium">
                        Batal
                    </a>
                </div>
            </form>
        </div>

        <!-- Info Box -->
        <div class="mt-6 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
            <h3 class="font-semibold text-yellow-800 mb-2">Ketentuan Cicilan:</h3>
            <ul class="text-sm text-yellow-700 space-y-1 list-disc list-inside">
                <li>Pengajuan cicilan akan diverifikasi oleh Tim Keuangan</li>
                <li>Jumlah cicilan yang disetujui bisa berbeda dengan yang diajukan</li>
                <li>Cicilan harus dibayar berurutan (cicilan 1 → 2 → 3...)</li>
                <li>Setiap pembayaran harus disertai bukti transfer</li>
                <li>Bukti transfer akan diverifikasi sebelum pembayaran dianggap sah</li>
            </ul>
        </div>
    </div>
</div>

<script>
function calculateInstallment() {
    const terms = document.getElementById('requested_terms').value;
    const total = {{ $invoice->total_tagihan }};
    
    if (terms && terms > 0) {
        const rounding = 1000;
        const baseCicilan = Math.floor(total / terms);
        const cicilanBulat = Math.floor(baseCicilan / rounding) * rounding;
        
        document.getElementById('calculation-preview').classList.remove('hidden');
        document.getElementById('estimated-amount').textContent = 
            'Rp ' + cicilanBulat.toLocaleString('id-ID');
    } else {
        document.getElementById('calculation-preview').classList.add('hidden');
    }
}
</script>
@endsection
