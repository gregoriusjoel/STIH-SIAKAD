@extends('layouts.admin')

@section('content')
    <div class="max-w-full mx-auto">
        {{-- Header Section --}}
        <div class="mb-10 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
            <div>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight">Hasil Kuisioner</h1>
                <p class="mt-2 text-slate-500 font-medium">Monitoring dan evaluasi hasil kuisioner kampus</p>
            </div>
        </div>

        {{-- Questionnaire List --}}
        <div class="grid grid-cols-1 gap-6">
            <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="px-8 py-6 border-b border-slate-50 bg-slate-50/50">
                    <h3 class="text-lg font-bold text-slate-800">Daftar Kuisioner</h3>
                </div>



                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/30">
                                <th class="px-8 py-5 text-xs font-black text-slate-500 uppercase tracking-widest cursor-pointer hover:text-primary transition-colors">
                                    <div class="flex items-center gap-2">
                                        Nama Kuisioner
                                        <span class="material-symbols-outlined text-[16px] text-slate-300">swap_vert</span>
                                    </div>
                                </th>
                                <th class="px-8 py-5 text-xs font-black text-slate-500 uppercase tracking-widest cursor-pointer hover:text-primary transition-colors">
                                    <div class="flex items-center gap-2">
                                        Total Responden
                                        <span class="material-symbols-outlined text-[16px] text-slate-300">swap_vert</span>
                                    </div>
                                </th>
                                <th class="px-8 py-5 text-xs font-black text-slate-500 uppercase tracking-widest cursor-pointer hover:text-primary transition-colors">
                                    <div class="flex items-center gap-2">
                                        Tahun Ajaran
                                        <span class="material-symbols-outlined text-[16px] text-slate-300">swap_vert</span>
                                    </div>
                                </th>
                                <th class="px-8 py-5 text-xs font-black text-slate-500 uppercase tracking-widest text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @foreach($types as $type)
                                <tr class="hover:bg-slate-50/50 transition-colors duration-200">
                                    <td class="px-8 py-6">
                                        <div class="flex items-center gap-4">
                                            <div class="p-3 bg-primary/10 rounded-2xl">
                                                <span class="material-symbols-outlined text-primary">analytics</span>
                                            </div>
                                            <div>
                                                <div class="text-base font-bold text-slate-800">{{ $type['name'] }}</div>
                                                <div class="text-xs text-slate-400 mt-1">Dibuat:
                                                    {{ \Carbon\Carbon::parse($type['created_at'])->format('d M Y') }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-8 py-6">
                                        <div
                                            class="inline-flex items-center gap-2 px-3 py-1 bg-slate-100 rounded-full text-slate-700 font-bold text-sm">
                                            <span class="material-symbols-outlined text-sm">groups</span>
                                            {{ number_format($type['total_respondents']) }}
                                        </div>
                                    </td>
                                    <td class="px-8 py-6">
                                        <div class="text-sm font-bold text-slate-700 italic">
                                            {{ $type['tahun_ajaran'] }}
                                        </div>
                                    </td>
                                    <td class="px-8 py-6">
                                        <div class="flex items-center justify-center gap-3">
                                            <a href="{{ route('admin.hasil-kuisioner.show', $type['id']) }}"
                                                class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-slate-200 rounded-xl text-sm font-bold text-slate-700 hover:bg-slate-50 hover:border-slate-300 transition-all shadow-sm">
                                                <span class="material-symbols-outlined text-[18px]">visibility</span>
                                                Detail
                                            </a>
                                            <a href="{{ route('admin.hasil-kuisioner.export-excel', array_merge(['type' => $type['id']], request()->all())) }}"
                                                class="inline-flex items-center gap-2 px-4 py-2 bg-primary rounded-xl text-xs font-bold text-white hover:bg-primary/90 transition-all shadow-lg shadow-primary/10">
                                                <span class="material-symbols-outlined text-[18px]">description</span>
                                                Excel
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    </div>
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const table = document.querySelector('table');
        const headers = table.querySelectorAll('th.cursor-pointer');
        const tbody = table.querySelector('tbody');
        let sortOrder = {};

        headers.forEach((header, index) => {
            header.addEventListener('click', () => {
                const rows = Array.from(tbody.querySelectorAll('tr'));
                const isNumeric = index === 1; // Total Responden is numeric
                const currentOrder = sortOrder[index] || 'asc';
                const nextOrder = currentOrder === 'asc' ? 'desc' : 'asc';
                
                rows.sort((a, b) => {
                    let aVal = a.querySelectorAll('td')[index].innerText.trim();
                    let bVal = b.querySelectorAll('td')[index].innerText.trim();

                    if (isNumeric) {
                        aVal = parseFloat(aVal.replace(/,/g, '')) || 0;
                        bVal = parseFloat(bVal.replace(/,/g, '')) || 0;
                    }

                    if (aVal < bVal) return nextOrder === 'asc' ? -1 : 1;
                    if (aVal > bVal) return nextOrder === 'asc' ? 1 : -1;
                    return 0;
                });

                // Clear and append sorted rows
                tbody.innerHTML = '';
                rows.forEach(row => tbody.appendChild(row));
                
                // Track order
                sortOrder[index] = nextOrder;
                
                // Visual feedback: Update icons
                headers.forEach(h => {
                    const icon = h.querySelector('.material-symbols-outlined');
                    if (icon) {
                        icon.innerText = 'swap_vert';
                        icon.classList.replace('text-primary', 'text-slate-300');
                    }
                });
                
                const currentIcon = header.querySelector('.material-symbols-outlined');
                if (currentIcon) {
                    currentIcon.innerText = nextOrder === 'asc' ? 'arrow_upward' : 'arrow_downward';
                    currentIcon.classList.replace('text-slate-300', 'text-primary');
                }
            });
        });
    });
</script>
@endpush
@endsection