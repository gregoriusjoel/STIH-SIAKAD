@extends('layouts.finance')

@section('page-title', $invoice->auto_generated_from_krs ? 'Konfirmasi Tagihan dari KRS' : 'Edit Tagihan')

@section('content')
<div class="px-4 md:px-0 animate-fade-in">
    {{-- Breadcrumbs & Back --}}
    <div class="mb-8">
        <a href="{{ route('finance.invoices.index') }}" 
           class="group inline-flex items-center gap-2 text-slate-400 hover:text-[#8B1538] transition-colors font-bold text-sm">
            <i class="fas fa-arrow-left transition-transform group-hover:-translate-x-1"></i>
            <span>Kembali ke Daftar Tagihan</span>
        </a>
    </div>

    {{-- Form Card --}}
    <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden">
        <div class="p-8 border-b border-slate-50 bg-slate-50/30">
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">
                {{ $invoice->auto_generated_from_krs ? 'Konfirmasi Tagihan dari KRS' : 'Edit Tagihan' }}
            </h1>
            <p class="text-sm text-slate-500 font-medium">
                @if($invoice->auto_generated_from_krs)
                    Tagihan ini dibuat otomatis saat mahasiswa submit KRS. Anda hanya perlu mengisi nominal dan detail pembayaran.
                @else
                    Lengkapi formulir di bawah ini untuk mengubah data tagihan.
                @endif
            </p>
        </div>

        <form action="{{ route('finance.invoices.update', $invoice) }}" method="POST" class="p-8 space-y-8">
            @csrf
            @method('PUT')

            {{-- Error Alerts --}}
            @if($errors->any())
                <div class="bg-red-50 border border-red-100 rounded-2xl p-4 animate-shake">
                    <div class="flex gap-3">
                        <i class="fas fa-exclamation-circle text-red-500 mt-0.5"></i>
                        <ul class="text-sm text-red-700 font-bold space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                {{-- Pilih Mahasiswa (Full Width in Grid) --}}
                <div class="md:col-span-2 space-y-2">
                    <label for="student_id" class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 ml-1">
                        Mahasiswa @if(!$invoice->auto_generated_from_krs)<span class="text-red-500">*</span>@endif
                    </label>
                    <div class="relative group">
                        <select name="student_id" id="student_id" 
                                {{ $invoice->auto_generated_from_krs ? 'disabled' : 'required' }}
                                class="w-full pl-12 pr-4 py-4 bg-slate-50 border-none rounded-2xl text-slate-800 font-bold text-sm focus:ring-2 focus:ring-[#8B1538] transition-all appearance-none cursor-pointer {{ $invoice->auto_generated_from_krs ? 'opacity-60' : '' }}">
                            <option value="">-- Pilih Mahasiswa --</option>
                            @foreach($students as $student)
                                <option value="{{ $student->id }}" {{ old('student_id', $invoice->student_id) == $student->id ? 'selected' : '' }}>
                                    {{ $student->nim }} - {{ $student->user->name ?? 'N/A' }}
                                </option>
                            @endforeach
                        </select>
                        @if($invoice->auto_generated_from_krs)
                        <input type="hidden" name="student_id" value="{{ $invoice->student_id }}">
                        @endif
                        <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-[#8B1538] transition-colors">
                            <i class="fas fa-user-graduate"></i>
                        </div>
                        <div class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-300 pointer-events-none transition-transform group-focus-within:rotate-180">
                            <i class="fas fa-chevron-down text-xs"></i>
                        </div>
                    </div>
                </div>

                {{-- Semester --}}
                <div class="space-y-2">
                    <label for="semester" class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 ml-1">
                        Semester @if(!$invoice->auto_generated_from_krs)<span class="text-red-500">*</span>@endif
                    </label>
                    <div class="relative group">
                        <select name="semester" id="semester" 
                                {{ $invoice->auto_generated_from_krs ? 'disabled' : 'required' }}
                                class="w-full pl-12 pr-4 py-4 bg-slate-50 border-none rounded-2xl text-slate-800 font-bold text-sm focus:ring-2 focus:ring-[#8B1538] transition-all appearance-none cursor-pointer {{ $invoice->auto_generated_from_krs ? 'opacity-60' : '' }}">
                            <option value="">-- Pilih --</option>
                            @for($i = 1; $i <= 14; $i++)
                                <option value="{{ $i }}" {{ old('semester', $invoice->semester) == $i ? 'selected' : '' }}>Semester {{ $i }}</option>
                            @endfor
                        </select>
                        @if($invoice->auto_generated_from_krs)
                        <input type="hidden" name="semester" value="{{ $invoice->semester }}">
                        @endif
                        <i class="fas fa-layer-group absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-[#8B1538] transition-colors"></i>
                    </div>
                </div>

                {{-- Tahun Ajaran --}}
                <div class="space-y-2">
                    <label for="tahun_ajaran" class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 ml-1">
                        Tahun Ajaran @if(!$invoice->auto_generated_from_krs)<span class="text-red-500">*</span>@endif
                    </label>
                    <div class="relative group">
                        <input type="text" name="tahun_ajaran" id="tahun_ajaran" 
                               value="{{ old('tahun_ajaran', $invoice->tahun_ajaran) }}" 
                               {{ $invoice->auto_generated_from_krs ? 'disabled' : 'required' }}
                               placeholder="2024/2025" 
                               class="w-full pl-12 pr-4 py-4 bg-slate-50 border-none rounded-2xl text-slate-800 font-bold text-sm focus:ring-2 focus:ring-[#8B1538] transition-all placeholder:text-slate-300 {{ $invoice->auto_generated_from_krs ? 'opacity-60' : '' }}">
                        @if($invoice->auto_generated_from_krs)
                        <input type="hidden" name="tahun_ajaran" value="{{ $invoice->tahun_ajaran }}">
                        @endif
                        <i class="fas fa-calendar-alt absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-[#8B1538] transition-colors"></i>
                    </div>
                </div>

                {{-- SKS Ambil --}}
                <div class="space-y-2">
                    <label for="sks_ambil" class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 ml-1">
                        SKS Ambil @if(!$invoice->auto_generated_from_krs)<span class="text-red-500">*</span>@endif
                    </label>
                    <div class="relative group">
                        <input type="number" name="sks_ambil" id="sks_ambil" 
                               value="{{ old('sks_ambil', $invoice->sks_ambil) }}" 
                               {{ $invoice->auto_generated_from_krs ? 'disabled' : 'required' }}
                               min="1" max="24"
                               class="w-full pl-12 pr-4 py-4 bg-slate-50 border-none rounded-2xl text-slate-800 font-bold text-sm focus:ring-2 focus:ring-[#8B1538] transition-all {{ $invoice->auto_generated_from_krs ? 'opacity-60' : '' }}">
                        @if($invoice->auto_generated_from_krs)
                        <input type="hidden" name="sks_ambil" value="{{ $invoice->sks_ambil }}">
                        @endif
                        <i class="fas fa-book-open absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-[#8B1538] transition-colors"></i>
                    </div>
                </div>

                {{-- Paket SKS Bayar --}}
                <div class="space-y-2">
                    <label for="paket_sks_bayar" class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 ml-1">
                        Paket SKS Bayar @if(!$invoice->auto_generated_from_krs)<span class="text-red-500">*</span>@endif
                    </label>
                    <div class="relative group">
                        <input type="number" name="paket_sks_bayar" id="paket_sks_bayar" 
                               value="{{ old('paket_sks_bayar', $invoice->paket_sks_bayar) }}" 
                               {{ $invoice->auto_generated_from_krs ? 'disabled' : 'required' }}
                               min="1" max="24"
                               class="w-full pl-12 pr-4 py-4 bg-slate-50 border-none rounded-2xl text-slate-800 font-bold text-sm focus:ring-2 focus:ring-[#8B1538] transition-all {{ $invoice->auto_generated_from_krs ? 'opacity-60' : '' }}">
                        @if($invoice->auto_generated_from_krs)
                        <input type="hidden" name="paket_sks_bayar" value="{{ $invoice->paket_sks_bayar }}">
                        @endif
                        <i class="fas fa-wallet absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-[#8B1538] transition-colors"></i>
                    </div>
                </div>

                {{-- Total Tagihan --}}
                <div class="md:col-span-2 space-y-2">
                    <label for="total_tagihan" class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 ml-1">
                        Total Tagihan (Rp) <span class="text-red-500">*</span>
                    </label>
                    <div class="relative group">
                        <div class="absolute left-4 top-1/2 -translate-y-1/2 font-black text-slate-400 group-focus-within:text-[#8B1538] transition-colors">Rp</div>
                        <input type="text" name="total_tagihan" id="total_tagihan" 
                               value="{{ old('total_tagihan', $invoice->total_tagihan) }}" 
                               required
                               placeholder="5.000.000" 
                               class="w-full pl-12 pr-4 py-5 bg-[#8B1538]/5 border-none rounded-2xl text-slate-800 font-black text-xl focus:ring-2 focus:ring-[#8B1538] transition-all placeholder:text-slate-200 tracking-tight">
                    </div>
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest ml-1">Nominal akan diformat otomatis</p>
                </div>

                {{-- VA Section (Hidden - set to true to enable) --}}
                @if(false)
                {{-- Bank Name --}}
                <div class="space-y-2">
                    <label for="bank_name" class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 ml-1">
                        Bank (Opsional)
                    </label>
                    <div class="relative group">
                        <select name="bank_name" id="bank_name"
                                class="w-full pl-12 pr-4 py-4 bg-white border-none rounded-2xl text-slate-800 font-bold text-sm focus:ring-2 focus:ring-blue-500 transition-all appearance-none cursor-pointer">
                            <option value="">-- Tidak menggunakan VA --</option>
                            <option value="BCA" {{ old('bank_name', $invoice->bank_name) == 'BCA' ? 'selected' : '' }}>BCA</option>
                            <option value="BNI" {{ old('bank_name', $invoice->bank_name) == 'BNI' ? 'selected' : '' }}>BNI</option>
                            <option value="BRI" {{ old('bank_name', $invoice->bank_name) == 'BRI' ? 'selected' : '' }}>BRI</option>
                            <option value="Mandiri" {{ old('bank_name', $invoice->bank_name) == 'Mandiri' ? 'selected' : '' }}>Bank Mandiri</option>
                            <option value="BSI" {{ old('bank_name', $invoice->bank_name) == 'BSI' ? 'selected' : '' }}>Bank Syariah Indonesia (BSI)</option>
                            <option value="BTN" {{ old('bank_name', $invoice->bank_name) == 'BTN' ? 'selected' : '' }}>BTN</option>
                            <option value="CIMB Niaga" {{ old('bank_name', $invoice->bank_name) == 'CIMB Niaga' ? 'selected' : '' }}>CIMB Niaga</option>
                            <option value="Danamon" {{ old('bank_name', $invoice->bank_name) == 'Danamon' ? 'selected' : '' }}>Bank Danamon</option>
                            <option value="Permata" {{ old('bank_name', $invoice->bank_name) == 'Permata' ? 'selected' : '' }}>Bank Permata</option>
                        </select>
                        <i class="fas fa-landmark absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-blue-500 transition-colors"></i>
                        <div class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-300 pointer-events-none transition-transform group-focus-within:rotate-180">
                            <i class="fas fa-chevron-down text-xs"></i>
                        </div>
                    </div>
                </div>

                {{-- VA Number --}}
                <div class="space-y-2">
                    <label for="va_number" class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 ml-1">
                        Nomor Virtual Account
                    </label>
                    <div class="relative group">
                        <input type="text" name="va_number" id="va_number" 
                               value="{{ old('va_number', $invoice->va_number) }}"
                               placeholder="Contoh: 8800123456789"
                               inputmode="numeric"
                               pattern="[0-9]*"
                               class="w-full pl-12 pr-4 py-4 bg-white border-none rounded-2xl text-slate-800 font-bold text-sm focus:ring-2 focus:ring-blue-500 transition-all placeholder:text-slate-300 tracking-widest font-mono">
                        <i class="fas fa-hashtag absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-blue-500 transition-colors"></i>
                    </div>
                    <p class="text-[10px] text-slate-400 font-bold ml-1">Hanya angka, tanpa spasi atau tanda baca</p>
                </div>
                @endif

                {{-- Allow Partial --}}
                <div class="md:col-span-2 flex items-center gap-4 p-5 bg-slate-50 rounded-2xl border border-slate-100/50 group cursor-pointer hover:bg-slate-100/50 transition-colors">
                    <div class="relative flex items-center justify-center size-6 shrink-0">
                        <input type="checkbox" name="allow_partial" id="allow_partial" value="1" 
                               {{ old('allow_partial', $invoice->allow_partial) ? 'checked' : '' }}
                               class="peer absolute inset-0 border-2 border-slate-200 rounded-lg bg-white text-[#8B1538] focus:ring-[#8B1538] transition-all cursor-pointer appearance-none checked:bg-[#8B1538] checked:border-[#8B1538]">
                        <i class="fas fa-check text-white scale-0 peer-checked:scale-100 transition-transform pointer-events-none text-[10px] z-10"></i>
                    </div>
                    <label for="allow_partial" class="flex-1 cursor-pointer">
                        <span class="block text-sm font-black text-slate-800 uppercase tracking-widest">Izinkan Cicilan</span>
                        <span class="block text-xs text-slate-400 font-medium">Mahasiswa dapat mengajukan skema pembayaran bertahap</span>
                    </label>
                </div>

                {{-- Notes --}}
                <div class="md:col-span-2 space-y-2">
                    <label for="notes" class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 ml-1">
                        Catatan Internal (Opsional)
                    </label>
                    <div class="relative group">
                        <textarea name="notes" id="notes" rows="4" 
                                  class="w-full pl-12 pr-4 py-4 bg-slate-50 border-none rounded-2xl text-slate-800 font-bold text-sm focus:ring-2 focus:ring-[#8B1538] transition-all placeholder:text-slate-300 min-h-[120px]"
                                  placeholder="Tambahkan detail khusus untuk tagihan ini...">{{ old('notes', $invoice->notes) }}</textarea>
                        <i class="fas fa-comment-alt absolute left-4 top-6 text-slate-400 group-focus-within:text-[#8B1538] transition-colors"></i>
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="pt-6 border-t border-slate-50 flex flex-col md:flex-row gap-4">
                <button type="submit" 
                        class="flex-1 px-8 py-4 bg-[#8B1538] hover:bg-[#6D1029] text-white rounded-2xl font-black text-sm uppercase tracking-[0.2em] transition-all shadow-lg shadow-red-900/10 active:scale-95 group">
                    <span class="flex items-center justify-center gap-3">
                        <i class="fas fa-save transition-transform group-hover:rotate-12"></i>
                        Simpan Perubahan
                    </span>
                </button>
                <a href="{{ route('finance.invoices.index') }}" 
                   class="px-8 py-4 bg-slate-50 hover:bg-slate-100 text-slate-400 font-black text-sm uppercase tracking-[0.2em] rounded-2xl transition-all text-center">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

<style>
    @keyframes fade-in {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in { animation: fade-in 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-5px); }
        75% { transform: translateX(5px); }
    }
    .animate-shake { animation: shake 0.4s ease-in-out; }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const totalInput = document.getElementById('total_tagihan');

        const formatRupiah = (val) => {
            return val.replace(/\D/g, '').replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        };

        if (totalInput) {
            totalInput.addEventListener('input', function() {
                let cursorPosition = this.selectionStart;
                let originalLength = this.value.length;

                this.value = formatRupiah(this.value);

                let newLength = this.value.length;
                this.selectionStart = this.selectionEnd = cursorPosition + (newLength - originalLength);
            });

            // Format existing value on page load
            if (totalInput.value) {
                totalInput.value = formatRupiah(totalInput.value);
            }

            // Clean value before submit
            totalInput.closest('form').addEventListener('submit', function() {
                totalInput.value = totalInput.value.replace(/\D/g, '');
            });
        }
    });
</script>
@endsection
