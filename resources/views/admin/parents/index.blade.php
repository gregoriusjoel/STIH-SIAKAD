@extends('layouts.admin')

@section('title', 'Data Orang Tua/Wali')
@section('page-title', 'Data Orang Tua/Wali')

@section('content')
    <div class="mb-6 flex flex-col items-start md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                <i class="fas fa-users mr-3 text-maroon"></i>
                Manajemen Orang Tua/Wali
            </h2>
            <p class="text-gray-600 text-sm mt-1">Kelola data orang tua/wali mahasiswa</p>
        </div>
        <div class="flex items-center gap-2 flex-shrink-0">
            <button type="button" onclick="document.getElementById('importModal').classList.remove('hidden')"
                class="bg-white dark:bg-gray-800 text-maroon dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/10 border-2 border-maroon/10 hover:border-maroon px-4 py-2 rounded-lg transition-all duration-200 flex items-center gap-2 shadow-sm text-sm font-bold">
                <i class="fas fa-file-import text-xs"></i>
                Import Data Orang Tua
            </button>
            <a href="{{ route('admin.parents.create') }}"
                class="bg-maroon text-white hover:bg-red-900 px-4 py-2 rounded-lg transition flex items-center shadow-md transform hover:scale-105 text-sm font-medium">
                <i class="fas fa-plus mr-2"></i>
                Tambah Orang Tua
            </a>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-maroon text-white">
                    <tr>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">
                            <i class=""></i>No
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">
                            <i class="fas fa-user mr-2"></i>Nama Orang Tua
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">
                            <i class="fas fa-user-graduate mr-2"></i>Mahasiswa
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">
                            <i class="fas fa-heart mr-2"></i>Hubungan
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">
                            <i class="fas fa-briefcase mr-2"></i>Pekerjaan
                        </th>
                        <th scope="col" class="px-6 py-4 text-center text-xs font-bold uppercase tracking-wider">
                            <i class="fas fa-cog mr-2"></i>Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($parents as $parent)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">
                                {{ ($parents->currentPage() - 1) * $parents->perPage() + $loop->iteration }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    @php
                                        // Determine actual parent name if user_id belongs to mahasiswa
                                        $parentName = $parent->user->name;
                                        $parentEmail = $parent->user->email;
                                        if ($parent->user && $parent->user->role === 'mahasiswa') {
                                            if ($parent->hubungan === 'ayah' && $parent->nama_ayah) $parentName = $parent->nama_ayah;
                                            elseif ($parent->hubungan === 'ibu' && $parent->nama_ibu) $parentName = $parent->nama_ibu;
                                            elseif ($parent->hubungan === 'wali' && $parent->nama_wali) $parentName = $parent->nama_wali;
                                            // Fallbacks if hubungan isn't set
                                            elseif ($parent->nama_ayah) $parentName = $parent->nama_ayah;
                                            elseif ($parent->nama_ibu) $parentName = $parent->nama_ibu;
                                            elseif ($parent->nama_wali) $parentName = $parent->nama_wali;
                                            $parentEmail = '- (Belum ada Akun Valid)';
                                        }
                                    @endphp
                                    <div
                                        class="h-10 w-10 rounded-full bg-maroon flex items-center justify-center text-white font-bold mr-3">
                                        {{ strtoupper(substr($parentName, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="text-sm font-semibold text-gray-900">{{ $parentName }}</div>
                                        <div class="text-xs text-gray-500">
                                            @if($parentEmail !== '- (Belum ada Akun Valid)')
                                            <i class="fas fa-envelope text-gray-400 mr-1"></i>
                                            @endif
                                            {{ $parentEmail }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($parent->mahasiswa)
                                    <div class="text-sm font-medium text-gray-900">{{ $parent->mahasiswa->user->name }}</div>
                                    <div class="text-xs text-gray-500">NIM: {{ $parent->mahasiswa->nim }}</div>
                                @else
                                    <div class="text-sm font-medium text-gray-400 italic">Belum Tertaut</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full 
                                        {{ $parent->hubungan == 'ayah' ? 'bg-blue-100 text-blue-800' : ($parent->hubungan == 'ibu' ? 'bg-pink-100 text-pink-800' : 'bg-gray-100 text-gray-800') }}">
                                    <i class="fas fa-user mr-2 text-sm"></i>
                                    {{ ucfirst($parent->hubungan) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                {{ $parent->pekerjaan ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                <div class="flex items-center justify-center space-x-2">
                                    <a href="{{ route('admin.parents.edit', $parent) }}"
                                        class="text-yellow-600 hover:text-yellow-900 transition p-2 hover:bg-yellow-50 rounded"
                                        title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.parents.destroy', $parent) }}" method="POST" class="inline delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="text-red-600 hover:text-red-900 transition p-2 hover:bg-red-50 rounded"
                                            title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="fas fa-inbox text-6xl text-gray-300 mb-4"></i>
                                    <p class="text-lg font-medium">Belum ada data orang tua/wali</p>
                                    <p class="text-sm text-gray-400 mt-1">Tambahkan data orang tua/wali pertama dengan klik
                                        tombol "Tambah Orang Tua"</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($parents->hasPages())
            <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                {{ $parents->links() }}
            </div>
        @endif
    </div>

    {{-- ═══════════════ MODAL: IMPORT ORANG TUA ═══════════════ --}}
    <div id="importModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm hidden"
        onclick="if(event.target===this) closeImportModal()">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-3xl max-h-[90vh] flex flex-col mx-4">
            <div class="flex items-center justify-between px-6 py-4 bg-maroon text-white rounded-t-2xl flex-shrink-0">
                <h3 class="text-lg font-bold flex items-center gap-2">
                    <i class="fas fa-file-import"></i> Import Data Orang Tua
                </h3>
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.import.template', ['type' => 'orang_tua', 'format' => 'xlsx']) }}" data-no-loader
                        class="text-xs bg-white/20 hover:bg-white/30 px-3 py-1.5 rounded-lg transition flex items-center gap-1.5">
                        <i class="fas fa-file-excel"></i> Template Excel
                    </a>
                    <a href="{{ route('admin.import.template', ['type' => 'orang_tua', 'format' => 'csv']) }}" data-no-loader
                        class="text-xs bg-white/20 hover:bg-white/30 px-3 py-1.5 rounded-lg transition flex items-center gap-1.5">
                        <i class="fas fa-file-csv"></i> Template CSV
                    </a>
                    <button onclick="closeImportModal()" class="text-white text-xl hover:text-white/80 transition">&times;</button>
                </div>
            </div>
            <div class="flex-1 overflow-y-auto p-6 space-y-5">
                <div id="imp-dropzone"
                    class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-xl p-8 text-center transition-all duration-300 hover:border-maroon dark:hover:border-red-500 hover:bg-maroon/5 cursor-pointer">
                    <input type="file" id="imp-file-input" class="hidden" accept=".csv,.xlsx,.xls">
                    <div id="imp-dropzone-content">
                        <div class="w-14 h-14 mx-auto mb-3 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                            <i class="fas fa-cloud-upload-alt text-2xl text-gray-400"></i>
                        </div>
                        <h4 class="text-base font-semibold text-gray-700 dark:text-gray-300 mb-1">Drag & drop file di sini</h4>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-3">atau klik untuk memilih file</p>
                        <p class="text-xs text-gray-400">Format: CSV, XLSX | Maksimal: 10MB</p>
                    </div>
                    <div id="imp-file-selected" class="hidden">
                        <div class="flex items-center justify-center gap-3">
                            <i class="fas fa-file-alt text-3xl text-maroon dark:text-red-400"></i>
                            <div class="text-left">
                                <p id="imp-filename" class="text-sm font-semibold text-gray-700 dark:text-gray-300"></p>
                                <p id="imp-filesize" class="text-xs text-gray-500"></p>
                            </div>
                            <button type="button" onclick="impClearFile()" class="ml-4 text-red-500 hover:text-red-700">
                                <i class="fas fa-times-circle text-xl"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" id="imp-skip-duplicates" checked
                            class="rounded border-gray-300 text-maroon focus:ring-maroon">
                        <span class="text-sm text-gray-700 dark:text-gray-300">Lewati data duplikat</span>
                    </label>
                    <div class="flex items-center gap-3">
                        <button type="button" id="imp-btn-preview" onclick="impPreviewFile()" disabled
                            class="bg-blue-600 hover:bg-blue-700 disabled:bg-gray-400 disabled:cursor-not-allowed text-white px-5 py-2 rounded-lg transition flex items-center gap-2 text-sm font-medium">
                            <i class="fas fa-eye"></i> Preview
                        </button>
                        <button type="button" id="imp-btn-import" onclick="impImportData()" disabled
                            class="bg-maroon hover:bg-red-900 disabled:bg-gray-400 disabled:cursor-not-allowed text-white px-5 py-2 rounded-lg transition flex items-center gap-2 text-sm font-medium">
                            <i class="fas fa-file-import"></i> Import
                        </button>
                    </div>
                </div>
                <div id="imp-progress" class="hidden">
                    <div class="flex items-center justify-between mb-2">
                        <span id="imp-progress-text" class="text-sm text-gray-600 dark:text-gray-400">Memproses...</span>
                    </div>
                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5 overflow-hidden">
                        <div class="bg-maroon h-2.5 rounded-full animate-pulse" style="width:100%"></div>
                    </div>
                </div>
                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-4 border border-gray-200 dark:border-gray-700">
                    <h4 class="text-sm font-bold text-gray-700 dark:text-gray-300 mb-2 flex items-center gap-2">
                        <i class="fas fa-columns text-maroon"></i> Kolom Template
                    </h4>
                    <div class="flex flex-wrap gap-2">
                        <span class="px-2 py-1 text-xs rounded-lg bg-red-100 text-red-700 font-semibold">nim_mahasiswa*</span>
                        <span class="px-2 py-1 text-xs rounded-lg bg-red-100 text-red-700 font-semibold">nama_ortu*</span>
                        <span class="px-2 py-1 text-xs rounded-lg bg-red-100 text-red-700 font-semibold">hubungan*</span>
                        <span class="px-2 py-1 text-xs rounded-lg bg-gray-100 text-gray-600">email</span>
                        <span class="px-2 py-1 text-xs rounded-lg bg-gray-100 text-gray-600">pekerjaan</span>
                        <span class="px-2 py-1 text-xs rounded-lg bg-gray-100 text-gray-600">phone</span>
                        <span class="px-2 py-1 text-xs rounded-lg bg-gray-100 text-gray-600">address</span>
                    </div>
                    <p class="text-xs text-gray-400 mt-2"><span class="text-red-500">*</span> = kolom wajib | hubungan: ayah, ibu, wali</p>
                </div>
                <div id="imp-preview-container" class="hidden">
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow border border-gray-200 dark:border-gray-700 overflow-hidden">
                        <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                            <h4 class="text-sm font-bold text-gray-800 dark:text-gray-100 flex items-center gap-2">
                                <i class="fas fa-table text-maroon"></i> Preview Data
                            </h4>
                            <div id="imp-preview-stats" class="flex items-center gap-3 text-xs"></div>
                        </div>
                        <div class="overflow-x-auto max-h-64">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700/50 sticky top-0"><tr id="imp-preview-header"></tr></thead>
                                <tbody id="imp-preview-body" class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700"></tbody>
                            </table>
                        </div>
                        <div id="imp-preview-more" class="hidden p-3 text-center text-xs text-gray-500 border-t border-gray-200 dark:border-gray-700"></div>
                    </div>
                </div>
                <div id="imp-validation-errors" class="hidden">
                    <div class="bg-red-50 dark:bg-red-900/20 rounded-xl p-5 border border-red-200 dark:border-red-800">
                        <h4 class="text-sm font-bold text-red-800 dark:text-red-300 flex items-center gap-2">
                            <i class="fas fa-exclamation-triangle"></i> Error Validasi
                        </h4>
                        <ul id="imp-error-list" class="mt-3 space-y-1.5 max-h-40 overflow-y-auto"></ul>
                    </div>
                </div>
                <div id="imp-results" class="hidden">
                    <div id="imp-results-container" class="rounded-xl p-5 border">
                        <h4 id="imp-results-title" class="text-sm font-bold flex items-center gap-2"></h4>
                        <div id="imp-results-summary" class="mt-3 grid grid-cols-3 gap-3"></div>
                        <div id="imp-results-details" class="mt-3"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // SweetAlert Delete Confirmation
            document.querySelectorAll('.delete-form').forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    Swal.fire({
                        title: 'Apakah Anda yakin?',
                        text: "Data orang tua ini akan dihapus permanen!",
                        icon: 'warning',
                        iconColor: '#7a1621',
                        showCancelButton: true,
                        confirmButtonColor: '#7a1621',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Ya, Hapus!',
                        cancelButtonText: 'Batal',
                        background: '#ffffff',
                        customClass: {
                            confirmButton: 'btn btn-danger',
                            cancelButton: 'btn btn-secondary'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        </script>

        @if(session('success'))
            <script>
                Swal.fire({
                    title: 'Berhasil!',
                    text: "{{ session('success') }}",
                    icon: 'success',
                    timer: 3000,
                    showConfirmButton: false,
                    background: '#ffffff',
                    customClass: {
                        popup: 'rounded-xl'
                    }
                });
            </script>
        @endif

        {{-- Import Modal JavaScript --}}
        <script>
            const IMP_TYPE = 'orang_tua';
            let impSelectedFile = null;

            const impDropzone = document.getElementById('imp-dropzone');
            const impFileInput = document.getElementById('imp-file-input');
            impDropzone.addEventListener('click', (e) => { if (!e.target.closest('button')) impFileInput.click(); });
            impDropzone.addEventListener('dragover', (e) => { e.preventDefault(); impDropzone.classList.add('border-maroon','bg-maroon/10'); });
            impDropzone.addEventListener('dragleave', () => { impDropzone.classList.remove('border-maroon','bg-maroon/10'); });
            impDropzone.addEventListener('drop', (e) => { e.preventDefault(); impDropzone.classList.remove('border-maroon','bg-maroon/10'); if (e.dataTransfer.files.length) impHandleFile(e.dataTransfer.files[0]); });
            impFileInput.addEventListener('change', (e) => { if (e.target.files.length) impHandleFile(e.target.files[0]); });
            document.addEventListener('keydown', (e) => { if (e.key==='Escape') closeImportModal(); });

            function impHandleFile(file) {
                const ext = file.name.split('.').pop().toLowerCase();
                if (!['csv','xlsx','xls'].includes(ext)) { Swal.fire('Format Tidak Didukung','Gunakan CSV atau XLSX.','error'); return; }
                if (file.size > 10*1024*1024) { Swal.fire('File Terlalu Besar','Maksimal 10MB.','error'); return; }
                impSelectedFile = file;
                document.getElementById('imp-dropzone-content').classList.add('hidden');
                document.getElementById('imp-file-selected').classList.remove('hidden');
                document.getElementById('imp-filename').textContent = file.name;
                document.getElementById('imp-filesize').textContent = (file.size/1024).toFixed(1)+' KB';
                document.getElementById('imp-btn-preview').disabled = false;
                ['imp-preview-container','imp-validation-errors','imp-results'].forEach(id => document.getElementById(id).classList.add('hidden'));
                document.getElementById('imp-btn-import').disabled = true;
            }
            function impClearFile() {
                impSelectedFile = null; impFileInput.value = '';
                document.getElementById('imp-dropzone-content').classList.remove('hidden');
                document.getElementById('imp-file-selected').classList.add('hidden');
                document.getElementById('imp-btn-preview').disabled = true;
                document.getElementById('imp-btn-import').disabled = true;
                ['imp-preview-container','imp-validation-errors','imp-results'].forEach(id => document.getElementById(id).classList.add('hidden'));
            }
            function closeImportModal() { document.getElementById('importModal').classList.add('hidden'); }

            async function impPreviewFile() {
                if (!impSelectedFile) return;
                const fd = new FormData(); fd.append('file', impSelectedFile);
                document.getElementById('imp-progress').classList.remove('hidden');
                try {
                    const r = await fetch(`/admin/import/${IMP_TYPE}/preview`, { method:'POST', body:fd, headers:{'X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content} });
                    const res = await r.json();
                    document.getElementById('imp-progress').classList.add('hidden');
                    if (res.success) {
                        impBuildPreview(res.data);
                        const v = res.data.validation||{};
                        if (v.valid) { document.getElementById('imp-btn-import').disabled=false; document.getElementById('imp-validation-errors').classList.add('hidden'); }
                        else { impShowErrors(v.errors||[]); document.getElementById('imp-btn-import').disabled=(v.validated_data?.length||0)===0; }
                    } else { Swal.fire('Error',res.message||'Error','error'); }
                } catch(e) { document.getElementById('imp-progress').classList.add('hidden'); Swal.fire('Error',e.message,'error'); }
            }

            function impBuildPreview(data) {
                const hdr=document.getElementById('imp-preview-header'), body=document.getElementById('imp-preview-body'), stats=document.getElementById('imp-preview-stats');
                hdr.innerHTML=''; body.innerHTML='';
                const cols=data.columns||[], rows=data.preview||[];
                cols.forEach(c=>{ const th=document.createElement('th'); th.className='px-3 py-2 text-left text-xs font-bold text-gray-500 uppercase tracking-wider whitespace-nowrap'; th.textContent=c; hdr.appendChild(th); });
                rows.slice(0,50).forEach(row=>{ const tr=document.createElement('tr'); tr.className='hover:bg-gray-50 dark:hover:bg-gray-700/50'; cols.forEach(c=>{ const td=document.createElement('td'); td.className='px-3 py-2 text-xs text-gray-700 dark:text-gray-300 whitespace-nowrap'; td.textContent=row[c]||'-'; tr.appendChild(td); }); body.appendChild(tr); });
                const v=data.validation||{};
                stats.innerHTML=`<span class="px-2 py-1 rounded-full bg-blue-100 text-blue-700 font-medium">Total: ${data.total_rows||0}</span><span class="px-2 py-1 rounded-full bg-green-100 text-green-700 font-medium">Valid: ${v.valid_rows||0}</span>${(v.duplicate_rows||0)>0?`<span class="px-2 py-1 rounded-full bg-yellow-100 text-yellow-700 font-medium">Duplikat: ${v.duplicate_rows}</span>`:''}`;
                const more=document.getElementById('imp-preview-more');
                if(data.total_rows>50){more.textContent=`Menampilkan 50 dari ${data.total_rows} baris`;more.classList.remove('hidden');}else{more.classList.add('hidden');}
                document.getElementById('imp-preview-container').classList.remove('hidden');
            }

            function impShowErrors(errors) {
                const list=document.getElementById('imp-error-list'); list.innerHTML='';
                if(!errors?.length){document.getElementById('imp-validation-errors').classList.add('hidden');return;}
                errors.slice(0,20).forEach(err=>{ const li=document.createElement('li'); li.className='flex items-start gap-2 text-xs text-red-700 dark:text-red-300'; const msg=Array.isArray(err.errors)?err.errors.join(', '):(err.errors||err.message||'Error'); li.innerHTML=`<i class="fas fa-times-circle mt-0.5 flex-shrink-0"></i><span><strong>Baris ${err.row||'?'}:</strong> ${msg}</span>`; list.appendChild(li); });
                if(errors.length>20){const li=document.createElement('li');li.className='text-xs text-red-600 italic';li.textContent=`... dan ${errors.length-20} error lainnya`;list.appendChild(li);}
                document.getElementById('imp-validation-errors').classList.remove('hidden');
            }

            async function impImportData() {
                if (!impSelectedFile) return;
                Swal.fire({title:'Konfirmasi Import',text:'Import data orang tua/wali?',icon:'question',iconColor:'#7a1621',showCancelButton:true,confirmButtonColor:'#7a1621',cancelButtonColor:'#6c757d',confirmButtonText:'Ya, Import!',cancelButtonText:'Batal'}).then(async(r)=>{ if(r.isConfirmed) await impPerformImport(); });
            }
            async function impPerformImport() {
                const fd=new FormData(); fd.append('file',impSelectedFile); fd.append('skip_duplicates',document.getElementById('imp-skip-duplicates').checked?'1':'0');
                document.getElementById('imp-progress').classList.remove('hidden'); document.getElementById('imp-btn-import').disabled=true;
                try {
                    const r=await fetch(`/admin/import/${IMP_TYPE}/import`,{method:'POST',body:fd,headers:{'X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content}});
                    const res=await r.json(); document.getElementById('imp-progress').classList.add('hidden');
                    impShowResults(res);
                } catch(e) { document.getElementById('imp-progress').classList.add('hidden'); Swal.fire('Error',e.message,'error'); document.getElementById('imp-btn-import').disabled=false; }
            }
            function impShowResults(result) {
                const c=document.getElementById('imp-results-container'),t=document.getElementById('imp-results-title'),s=document.getElementById('imp-results-summary'),d=document.getElementById('imp-results-details');
                if(result.success){c.className='rounded-xl p-5 border bg-green-50 border-green-200';t.innerHTML='<i class="fas fa-check-circle text-green-600"></i><span class="text-green-800">Import Berhasil!</span>';}
                else{c.className='rounded-xl p-5 border bg-red-50 border-red-200';t.innerHTML='<i class="fas fa-times-circle text-red-600"></i><span class="text-red-800">Import Gagal</span>';}
                const sm=result.result?.summary||{};
                s.innerHTML=`<div class="text-center p-3 bg-white rounded-lg"><div class="text-xl font-bold text-gray-800">${sm.total||0}</div><div class="text-xs text-gray-500">Total</div></div><div class="text-center p-3 bg-white rounded-lg"><div class="text-xl font-bold text-green-600">${sm.success||0}</div><div class="text-xs text-gray-500">Berhasil</div></div><div class="text-center p-3 bg-white rounded-lg"><div class="text-xl font-bold text-red-600">${sm.failed||0}</div><div class="text-xs text-gray-500">Gagal</div></div>`;
                const failed=result.result?.results?.failed||[];
                d.innerHTML=failed.length?`<h5 class="font-semibold text-red-700 mb-1 text-xs">Detail Error:</h5><ul class="space-y-1 text-xs text-red-600 max-h-32 overflow-y-auto">${failed.map(f=>`<li>Baris ${f.row}: ${f.error}</li>`).join('')}</ul>`:'';
                document.getElementById('imp-results').classList.remove('hidden');
                if(result.success) setTimeout(()=>window.location.reload(),2500);
            }
        </script>
    @endpush
@endsection