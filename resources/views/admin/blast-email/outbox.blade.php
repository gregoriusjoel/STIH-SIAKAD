@extends('layouts.admin')

@section('title', 'Outbox - Blast Email')
@section('page-title', 'Kotak Keluar Email')

@push('styles')
<style>
    /* Gradient text */
    .text-gradient-maroon {
        background: linear-gradient(135deg, #9f1239, #be123c);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
</style>
@endpush

@section('content')
<div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
    <div>
        <h3 class="text-2xl font-bold text-slate-800 flex items-center gap-2.5 tracking-tight">
            <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center shadow-sm">
                <i class="fas fa-paper-plane text-lg"></i>
            </div>
            Kotak Keluar <span class="text-gradient-maroon">(Outbox)</span>
        </h3>
        <p class="text-sm text-slate-500 mt-2 font-medium max-w-2xl">Kelola antrean dan jadwal pengiriman blast email Anda.</p>
    </div>
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.blast-email.index') }}" class="px-5 py-2.5 rounded-xl bg-white border border-slate-200 text-slate-700 font-bold text-sm hover:bg-slate-50 transition-colors shadow-sm flex items-center gap-2">
            <i class="fas fa-arrow-left"></i> Kembali ke Komposisi
        </a>
    </div>
</div>

<div class="bg-white rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden border border-slate-100">
    <div class="p-6 border-b border-slate-100 bg-slate-50/50 flex flex-col md:flex-row items-center justify-between gap-4">
        <h4 class="font-bold text-slate-800">Daftar Antrean Email</h4>
    </div>

    @if(session('success'))
        <div class="m-6 p-4 rounded-xl bg-emerald-50 text-emerald-700 border border-emerald-100 flex items-center gap-3">
            <i class="fas fa-check-circle text-emerald-500"></i>
            <span class="font-medium text-sm">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="m-6 p-4 rounded-xl bg-red-50 text-red-700 border border-red-100 flex items-center gap-3">
            <i class="fas fa-exclamation-circle text-red-500"></i>
            <span class="font-medium text-sm">{{ session('error') }}</span>
        </div>
    @endif

    @if($errors->any())
        <div class="m-6 p-4 rounded-xl bg-red-50 text-red-700 border border-red-100">
            <ul class="list-disc pl-5 text-sm font-medium space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 border-y border-slate-100">
                    <th class="py-4 px-6 text-[11px] uppercase tracking-widest font-bold text-slate-500 w-16">No</th>
                    <th class="py-4 px-6 text-[11px] uppercase tracking-widest font-bold text-slate-500">Penerima</th>
                    <th class="py-4 px-6 text-[11px] uppercase tracking-widest font-bold text-slate-500">Subjek / Tipe</th>
                    <th class="py-4 px-6 text-[11px] uppercase tracking-widest font-bold text-slate-500">Jadwal Kirim</th>
                    <th class="py-4 px-6 text-[11px] uppercase tracking-widest font-bold text-slate-500">Status</th>
                    <th class="py-4 px-6 text-[11px] uppercase tracking-widest font-bold text-slate-500 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($outboxes as $index => $outbox)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="py-4 px-6 font-medium text-slate-600 text-sm">
                            {{ $outboxes->firstItem() + $index }}
                        </td>
                        <td class="py-4 px-6">
                            <div class="font-bold text-slate-800 text-sm">{{ $outbox->mahasiswa->user->name ?? 'Tanpa Nama' }}</div>
                            <div class="text-[11px] text-slate-500 font-medium">{{ $outbox->target_email }}</div>
                        </td>
                        <td class="py-4 px-6">
                            <div class="font-bold text-slate-800 text-sm truncate max-w-[200px]" title="{{ $outbox->subject }}">{{ $outbox->subject ?? 'Tanpa Subjek' }}</div>
                            <div class="text-[10px] uppercase tracking-widest font-bold mt-1">
                                @if($outbox->is_credentials_mode)
                                    <span class="text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded border border-indigo-100"><i class="fas fa-key mr-1"></i> Kredensial Sistem</span>
                                @else
                                    <span class="text-slate-500 bg-slate-100 px-2 py-0.5 rounded border border-slate-200"><i class="fas fa-envelope mr-1"></i> Email Biasa</span>
                                @endif
                            </div>
                        </td>
                        <td class="py-4 px-6">
                            @if($outbox->scheduled_at)
                                <div class="font-bold text-slate-800 text-sm">{{ $outbox->scheduled_at->format('d M Y') }}</div>
                                <div class="text-xs text-slate-500 font-medium">{{ $outbox->scheduled_at->format('H:i') }}</div>
                            @else
                                <span class="px-2.5 py-1 rounded-md bg-amber-50 text-amber-600 text-[10px] font-bold uppercase tracking-wider border border-amber-100">
                                    Segera
                                </span>
                            @endif
                        </td>
                        <td class="py-4 px-6">
                            @if($outbox->status === 'pending')
                                <span class="px-2.5 py-1 rounded-md bg-sky-50 text-sky-600 text-[10px] font-bold uppercase tracking-wider border border-sky-100"><i class="fas fa-hourglass-half mr-1"></i> Menunggu</span>
                            @elseif($outbox->status === 'sent')
                                <span class="px-2.5 py-1 rounded-md bg-emerald-50 text-emerald-600 text-[10px] font-bold uppercase tracking-wider border border-emerald-100"><i class="fas fa-check mr-1"></i> Terkirim</span>
                            @elseif($outbox->status === 'cancelled')
                                <span class="px-2.5 py-1 rounded-md bg-slate-100 text-slate-500 text-[10px] font-bold uppercase tracking-wider border border-slate-200"><i class="fas fa-ban mr-1"></i> Dibatalkan</span>
                            @else
                                <span class="px-2.5 py-1 rounded-md bg-red-50 text-red-600 text-[10px] font-bold uppercase tracking-wider border border-red-100"><i class="fas fa-times mr-1"></i> Gagal</span>
                                @if($outbox->error_message)
                                    <div class="text-[10px] text-red-400 mt-1 truncate max-w-[150px]" title="{{ $outbox->error_message }}">{{ $outbox->error_message }}</div>
                                @endif
                            @endif
                        </td>
                        <td class="py-4 px-6 text-center">
                            @if($outbox->status === 'pending' || $outbox->status === 'failed')
                                <div class="flex items-center justify-center gap-2">
                                    <button type="button" onclick="editOutbox({{ $outbox->id }}, '{{ addslashes($outbox->subject) }}', '{{ addslashes($outbox->greeting) }}', '{{ preg_replace('/\r?\n/', '\\n', addslashes($outbox->message_body)) }}', {{ $outbox->is_credentials_mode ? 'true' : 'false' }})" class="w-8 h-8 rounded-lg bg-sky-50 text-sky-600 flex items-center justify-center hover:bg-sky-100 hover:text-sky-700 transition-colors" title="Edit Pesan">
                                        <i class="fas fa-edit text-xs"></i>
                                    </button>
                                    <form action="{{ route('admin.blast-email.outbox.destroy', $outbox->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pengiriman email ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-8 h-8 rounded-lg bg-red-50 text-red-600 flex items-center justify-center hover:bg-red-100 hover:text-red-700 transition-colors" title="Batalkan Pengiriman">
                                            <i class="fas fa-times text-xs"></i>
                                        </button>
                                    </form>
                                </div>
                            @else
                                <span class="text-[11px] text-slate-400 font-medium italic">-</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-12 text-center text-slate-400">
                            <i class="fas fa-inbox text-4xl mb-3 text-slate-200"></i>
                            <p class="font-medium text-sm">Tidak ada email dalam antrean.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($outboxes->hasPages())
        <div class="p-6 border-t border-slate-100 bg-slate-50/50">
            {{ $outboxes->links() }}
        </div>
    @endif
</div>

<!-- Edit Modal -->
<div id="editModal" class="fixed inset-0 z-[100] hidden">
    <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm" onclick="closeEditModal()"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-lg">
        <div class="bg-white rounded-3xl shadow-2xl p-8 border border-slate-100">
            <div class="flex items-center justify-between mb-6">
                <h4 class="text-xl font-black text-slate-800">Edit Pesan Email</h4>
                <button onclick="closeEditModal()" class="w-8 h-8 flex items-center justify-center rounded-full bg-slate-50 text-slate-400 hover:bg-slate-100 hover:text-slate-600 transition-colors">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form id="editForm" method="POST" action="">
                @csrf
                @method('PUT')
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-[11px] uppercase tracking-widest font-bold text-slate-500 mb-1.5">Subjek</label>
                        <input type="text" id="edit_subject" name="subject" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:ring-4 focus:ring-sky-500/10 focus:border-sky-500 transition-all">
                    </div>
                    <div>
                        <label class="block text-[11px] uppercase tracking-widest font-bold text-slate-500 mb-1.5">Salam Pembuka</label>
                        <input type="text" id="edit_greeting" name="greeting" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:ring-4 focus:ring-sky-500/10 focus:border-sky-500 transition-all">
                    </div>
                    <div>
                        <label class="block text-[11px] uppercase tracking-widest font-bold text-slate-500 mb-1.5">Teks Utama</label>
                        <textarea id="edit_message" name="message_body" rows="6" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:ring-4 focus:ring-sky-500/10 focus:border-sky-500 transition-all resize-none"></textarea>
                    </div>
                </div>
                
                <div class="mt-8 flex justify-end gap-3">
                    <button type="button" onclick="closeEditModal()" class="px-5 py-2.5 rounded-xl font-bold text-sm text-slate-600 bg-slate-100 hover:bg-slate-200 transition-colors">Batal</button>
                    <button type="submit" class="px-5 py-2.5 rounded-xl font-bold text-sm text-white bg-sky-500 hover:bg-sky-600 transition-colors shadow-lg shadow-sky-500/20">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function editOutbox(id, subject, greeting, message, isCredentials) {
        document.getElementById('editForm').action = `/admin/blast-email/outbox/${id}`;
        document.getElementById('edit_subject').value = subject;
        document.getElementById('edit_greeting').value = greeting;
        document.getElementById('edit_message').value = message;
        
        if (isCredentials) {
            document.getElementById('edit_message').closest('div').classList.add('hidden');
        } else {
            document.getElementById('edit_message').closest('div').classList.remove('hidden');
        }
        
        document.getElementById('editModal').classList.remove('hidden');
    }
    
    function closeEditModal() {
        document.getElementById('editModal').classList.add('hidden');
    }
</script>
@endpush
@endsection
