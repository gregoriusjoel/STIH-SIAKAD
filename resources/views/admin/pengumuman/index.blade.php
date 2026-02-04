@extends('layouts.admin')

@section('title', 'Pengumuman')
@section('page-title', 'Pengumuman')

@section('content')
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                <i class="fas fa-bullhorn mr-3 text-maroon"></i>
                Daftar Pengumuman
            </h2>
            <p class="text-gray-600 text-sm mt-1">Kelola informasi dan pengumuman untuk sistem</p>
        </div>
        <a href="{{ route('admin.pengumuman.create') }}"
            class="bg-maroon text-white hover:bg-red-900 px-6 py-3 rounded-lg transition flex items-center shadow-md">
            <i class="fas fa-plus mr-2"></i>
            Buat Pengumuman
        </a>
    </div>

<div class="bg-white rounded-xl shadow-lg border-t-4 border-maroon overflow-hidden">
    

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 border-separate" style="border-spacing: 0;">
            <thead class="bg-maroon text-white rounded-t-xl">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider rounded-tl-xl">
                        NO
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">
                        <i class="fas fa-bullhorn mr-2"></i>JUDUL
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">
                        <i class="fas fa-calendar-alt mr-2"></i>TANGGAL PUBLIKASI
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">
                        <i class="fas fa-users mr-2"></i>TARGET
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider rounded-tr-xl">
                        <i class="fas fa-cog mr-2"></i>AKSI
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($pengumumans as $p)
                <tr class="hover:bg-blue-50 transition">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">{{ $loop->iteration }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-maroon">{{ $p->judul }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $p->published_at ? \Carbon\Carbon::parse($p->published_at)->format('d M Y H:i') : '-' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm capitalize">
                        @if($p->target == 'semua')
                            <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-semibold">Semua</span>
                        @elseif($p->target == 'dosen')
                            <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs font-semibold">Dosen</span>
                        @elseif($p->target == 'mahasiswa')
                            <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-semibold">Mahasiswa</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex items-center space-x-2">
                            <a href="{{ route('admin.pengumuman.edit', $p) }}"
                                class="text-indigo-600 hover:text-indigo-900 transition" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.pengumuman.destroy', $p) }}" method="POST"
                                class="inline delete-form">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 transition"
                                    title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="p-4">
        {{ $pengumumans->links() }}
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
                text: "Data pengumuman ini akan dihapus permanen!",
                icon: 'warning',
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
@endpush
@endsection