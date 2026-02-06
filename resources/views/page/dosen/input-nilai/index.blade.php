@extends('layouts.app')

@section('title', 'Input Nilai | Portal Dosen')
@section('header_title', 'Input Nilai')

@section('content')
    <div class="px-6 md:px-8 w-full flex flex-col gap-6 pt-6">

        <!-- Class Selection Card -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-medium text-gray-900 mb-4">Pilih Mata Kuliah</h2>
            <form action="{{ route('dosen.input-nilai') }}" method="GET" class="flex flex-col md:flex-row gap-4 items-end">
                <div class="w-full md:w-1/3">
                    <label for="class_id" class="block text-sm font-medium text-gray-700 mb-1">Mata Kuliah & Kelas</label>
                    <select id="class_id" name="class_id"
                        class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm rounded-md">
                        <option value="">-- Pilih Mata Kuliah --</option>
                        @foreach($classes as $c)
                            <option value="{{ $c['id'] }}" {{ request('class_id') == $c['id'] ? 'selected' : '' }}>
                                {{ $c['name'] }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="w-full md:w-auto">
                    <button type="submit"
                        class="w-full inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        Tampilkan Mahasiswa
                    </button>
                </div>
            </form>
        </div>

        @if(request('class_id'))
            <!-- Grading Form -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 flex flex-col md:flex-row justify-between items-center gap-4">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">Form Input Nilai</h3>
                        <p class="text-sm text-gray-500">Pemrograman Web (IF-A) - Semester Ganjil 2023/2024</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <button
                            class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none">
                            <i class="fas fa-file-excel mr-2 text-green-600"></i> Import Excel
                        </button>
                        <button
                            class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none">
                            <i class="fas fa-save mr-2 text-gray-500"></i> Simpan Draft
                        </button>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-10">
                                    No</th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIM
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama
                                    Mahasiswa</th>
                                <th scope="col"
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-24">
                                    Tugas (20%)</th>
                                <th scope="col"
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-24">
                                    UTS (30%)</th>
                                <th scope="col"
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-24">
                                    UAS (50%)</th>
                                <th scope="col"
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-24">
                                    Akhir</th>
                                <th scope="col"
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-24">
                                    Grade</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($students as $index => $student)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $index + 1 }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $student['nim'] }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $student['name'] }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <input type="number" min="0" max="100"
                                            class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm text-center"
                                            placeholder="0">
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <input type="number" min="0" max="100"
                                            class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm text-center"
                                            placeholder="0">
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <input type="number" min="0" max="100"
                                            class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm text-center"
                                            placeholder="0">
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-bold text-gray-900">-</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-bold text-gray-900">-</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-10 text-center text-gray-500">
                                        Tidak ada data mahasiswa.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end">
                    <button type="button"
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        <i class="fas fa-check mr-2"></i> Simpan Nilai
                    </button>
                </div>
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-12">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-400">
                    <i class="fas fa-arrow-up text-2xl"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900">Silakan pilih mata kuliah terlebih dahulu</h3>
                <p class="text-gray-500 max-w-sm mx-auto mt-2">Pilih mata kuliah dan kelas melalui dropdown di atas untuk
                    memulai penginputan nilai mahasiswa.</p>
            </div>
        @endif

    </div>
@endsection