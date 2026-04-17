@extends('layouts.mahasiswa')

@section('title', 'Upload Bukti Pembayaran')

@section('content')
<div class="max-w-none mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <!-- Header & Back Button -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <a href="{{ route('mahasiswa.invoices.show', $invoice) }}" 
               class="inline-flex items-center text-sm font-medium text-slate-500 hover:text-[#8B1538] transition-colors mb-2">
                <i class="fas fa-arrow-left mr-2"></i> Kembali ke Detail Tagihan
            </a>
            <h1 class="text-2xl sm:text-3xl font-bold text-slate-800 tracking-tight">
                Upload Bukti Pembayaran
            </h1>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-8">
        <!-- Installment Summary Card -->
        <div class="bg-gradient-to-br from-[#8B1538] to-[#6b102b] rounded-2xl shadow-lg shadow-red-900/10 p-6 text-white relative overflow-hidden">
            <!-- Decorative circle -->
            <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-white/10 rounded-full blur-xl"></div>
            <div class="absolute bottom-0 left-0 -mb-4 -ml-4 w-32 h-32 bg-black/10 rounded-full blur-xl"></div>

            <div class="relative z-10 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h2 class="text-white/80 text-sm font-medium uppercase tracking-wider mb-1">Pembayaran Untuk</h2>
                    <div class="flex items-baseline gap-2">
                        <span class="text-2xl font-bold">Cicilan Ke-{{ $installment->installment_no }}</span>
                        <span class="text-white/60 text-sm font-medium">Semester {{ $invoice->semester }}</span>
                    </div>
                    <p class="text-white/70 text-xs mt-1">TA {{ $invoice->tahun_ajaran }}</p>
                </div>
                <div class="text-left sm:text-right">
                    <h2 class="text-white/80 text-sm font-medium uppercase tracking-wider mb-1">Total Tagihan</h2>
                    <p class="text-3xl font-bold">Rp {{ number_format($installment->amount, 0, ',', '.') }}</p>
                    @if($installment->due_date)
                        <div class="inline-flex items-center gap-1.5 mt-2 px-3 py-1 bg-white/10 rounded-full backdrop-blur-sm border border-white/10">
                            <i class="far fa-clock text-xs"></i>
                            <span class="text-xs font-medium">Jatuh Tempo: {{ $installment->due_date->format('d M Y') }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Upload Form Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="p-6 sm:p-8">
                <form action="{{ route('mahasiswa.payment-proofs.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    <input type="hidden" name="installment_id" value="{{ $installment->id }}">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Transfer Date -->
                        <div class="space-y-2">
                            <label for="transfer_date" class="block text-sm font-semibold text-slate-700">
                                Tanggal Transfer <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                    <i class="far fa-calendar-alt"></i>
                                </span>
                                <input type="date" name="transfer_date" id="transfer_date" 
                                       max="{{ date('Y-m-d') }}"
                                       class="block w-full pl-10 pr-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 text-sm placeholder-slate-400 focus:outline-none focus:border-[#8B1538] focus:ring-1 focus:ring-[#8B1538] transition-colors @error('transfer_date') border-red-500 @enderror"
                                       value="{{ old('transfer_date', date('Y-m-d')) }}" required>
                            </div>
                            @error('transfer_date')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Amount Submitted -->
                        <div class="space-y-2">
                            <label for="amount_submitted" class="block text-sm font-semibold text-slate-700">
                                Nominal Dibayar <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-500 font-bold">
                                    Rp
                                </span>
                                <input type="hidden" name="amount_submitted" id="amount_submitted_raw" value="{{ old('amount_submitted', $installment->amount) }}">
                                <input type="text" id="amount_submitted_display" 
                                       class="block w-full pl-10 pr-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 text-sm font-medium placeholder-slate-400 focus:outline-none focus:border-[#8B1538] focus:ring-1 focus:ring-[#8B1538] transition-colors @error('amount_submitted') border-red-500 @enderror"
                                       value="{{ number_format(old('amount_submitted', $installment->amount), 0, ',', '.') }}" 
                                       inputmode="numeric"
                                       required>
                            </div>
                            @error('amount_submitted')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                            @if(!$invoice->allow_partial)
                                <p class="text-[10px] text-slate-500 italic">* Nominal harus sesuai tagihan cicilan</p>
                            @endif
                        </div>
                    </div>

                    <!-- Payment Method -->
                    <div class="space-y-2">
                        <label for="method" class="block text-sm font-semibold text-slate-700">
                            Metode Pembayaran <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                <i class="far fa-credit-card"></i>
                            </span>
                            <select name="method" id="method" 
                                    required
                                    class="block w-full pl-10 pr-10 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 text-sm focus:outline-none focus:border-[#8B1538] focus:ring-1 focus:ring-[#8B1538] transition-colors appearance-none @error('method') border-red-500 @enderror">
                                <option value="">Pilih Metode Pembayaran...</option>
                                <option value="Transfer Bank" {{ old('method') == 'Transfer Bank' ? 'selected' : '' }}>Transfer Bank</option>
                                <option value="VA" {{ old('method') == 'VA' ? 'selected' : '' }}>Virtual Account</option>
                                <option value="E-Wallet" {{ old('method') == 'E-Wallet' ? 'selected' : '' }}>E-Wallet (OVO/Gopay/ShopeePay)</option>
                                <option value="Tunai" {{ old('method') == 'Tunai' ? 'selected' : '' }}>Tunai / Teller</option>
                            </select>
                            <span class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400">
                                <i class="fas fa-chevron-down text-xs"></i>
                            </span>
                        </div>
                        @error('method')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- File Upload Area -->
                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-slate-700">
                            Bukti Transfer <span class="text-red-500">*</span>
                        </label>
                        <div class="relative group">
                            <input type="file" name="file" id="file" accept=".jpg,.jpeg,.png,.pdf" required
                                   class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-20"
                                   onchange="updateFileName(this)">
                            <div class="border-2 border-dashed border-slate-300 rounded-xl p-8 transition-all group-hover:border-[#8B1538] group-hover:bg-red-50/10 text-center relative z-10 bg-slate-50">
                                <div class="w-12 h-12 bg-slate-200 text-slate-400 rounded-full flex items-center justify-center mx-auto mb-3 group-hover:bg-[#8B1538] group-hover:text-white transition-colors duration-300">
                                    <i class="fas fa-cloud-upload-alt text-xl"></i>
                                </div>
                                <h3 class="text-sm font-bold text-slate-700 mb-1">
                                    <span class="text-[#8B1538] group-hover:underline">Klik untuk upload</span> atau drag & drop
                                </h3>
                                <p class="text-xs text-slate-500 mb-2">JPG, PNG, atau PDF (Max. 2MB)</p>
                                <p id="file-name" class="text-sm font-medium text-[#8B1538] hidden mt-2 py-1 px-3 bg-red-50 rounded-lg inline-block"></p>
                            </div>
                        </div>
                        @error('file')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Notes -->
                    <div class="space-y-2">
                        <label for="student_notes" class="block text-sm font-semibold text-slate-700">
                            Catatan <span class="text-slate-400 font-normal">(Opsional)</span>
                        </label>
                        <textarea name="student_notes" id="student_notes" rows="3"
                                  class="block w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 text-sm placeholder-slate-400 focus:outline-none focus:border-[#8B1538] focus:ring-1 focus:ring-[#8B1538] transition-colors resize-none"
                                  placeholder="Tambahkan catatan jika diperlukan...">{{ old('student_notes') }}</textarea>
                    </div>

                    <!-- Actions -->
                    <div class="pt-4 flex flex-col sm:flex-row gap-4">
                        <button type="submit" 
                                class="flex-1 px-6 py-3.5 bg-[#8B1538] text-white rounded-xl hover:bg-[#7a1230] font-bold text-sm uppercase tracking-wide shadow-md shadow-red-900/10 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200">
                            Upload Bukti Pembayaran
                        </button>
                        <a href="{{ route('mahasiswa.invoices.show', $invoice) }}" 
                           class="px-6 py-3.5 bg-slate-100 text-slate-600 rounded-xl hover:bg-slate-200 font-bold text-sm uppercase tracking-wide text-center transition-colors">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Info Card -->
        <div class="bg-blue-50 border border-blue-100 rounded-2xl p-6 flex gap-4">
            <div class="shrink-0">
                <div class="w-10 h-10 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center">
                    <i class="fas fa-info"></i>
                </div>
            </div>
            <div>
                <h4 class="text-sm font-bold text-blue-800 mb-1">Informasi Penting</h4>
                <ul class="text-xs text-blue-700/80 space-y-1.5 list-disc list-inside">
                    <li>Pastikan nominal transfer sesuai dengan tagihan cicilan.</li>
                    <li>Foto bukti transfer harus jelas dan terbaca (tidak blur).</li>
                    <li>Pembayaran akan diverifikasi oleh Admin Keuangan dalam 1x24 jam kerja.</li>
                    <li>Status pembayaran akan berubah menjadi <strong>LUNAS</strong> setelah disetujui.</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
    function updateFileName(input) {
        const fileNameElement = document.getElementById('file-name');
        if (input.files && input.files.length > 0) {
            const file = input.files[0];
            // Validate file format
            const allowedExtensions = ['.jpg', '.jpeg', '.png', '.pdf'];
            const fileName = file.name.toLowerCase();
            const isValidExtension = allowedExtensions.some(ext => fileName.endsWith(ext));

            if (!isValidExtension) {
                Swal.fire({
                    icon: 'error',
                    title: 'Format File Tidak Valid!',
                    html: 'File yang diizinkan: <strong>JPG, JPEG, PNG, atau PDF</strong>.<br><br>File "<strong>' + file.name + '</strong>" tidak dapat diupload.',
                    confirmButtonColor: '#8B1538',
                    confirmButtonText: 'OK'
                });
                input.value = '';
                fileNameElement.classList.add('hidden');
                return;
            }

            fileNameElement.textContent = file.name;
            fileNameElement.classList.remove('hidden');
        } else {
            fileNameElement.classList.add('hidden');
        }
    }

    // Amount formatting with thousand separator dots
    document.addEventListener('DOMContentLoaded', function() {
        const display = document.getElementById('amount_submitted_display');
        const hidden = document.getElementById('amount_submitted_raw');

        function formatNumber(num) {
            return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }

        display.addEventListener('input', function() {
            // Strip non-digits
            let raw = this.value.replace(/\D/g, '');
            // Update hidden input with raw value
            hidden.value = raw;
            // Format display with dots
            this.value = raw ? formatNumber(raw) : '';
        });
    });
</script>
@endsection
