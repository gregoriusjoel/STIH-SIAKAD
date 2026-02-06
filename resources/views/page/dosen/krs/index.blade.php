@extends('layouts.app')

@section('title', 'Persetujuan KRS')
@section('header_title', 'Persetujuan KRS')

@section('content')
    <div class="px-6 md:px-8 w-full flex flex-col gap-6 pt-6">
        <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Persetujuan KRS</h1>
                <p class="text-gray-500 mt-2 text-base">Daftar mahasiswa yang menunggu persetujuan Kartu Rencana Studi.</p>
            </div>
            <div class="flex items-center gap-3">
                <div class="relative">
                    <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    <input type="text" placeholder="Cari mahasiswa..."
                        class="pl-10 pr-4 py-2 border-none rounded-lg bg-white shadow-sm focus:ring-2 focus:ring-[#8B1538] text-sm w-64">
                </div>
                <button
                    class="bg-white border border-gray-200 text-gray-700 px-4 py-2 rounded-lg font-medium text-sm hover:bg-gray-50 flex items-center shadow-sm">
                    <i class="fas fa-filter mr-2 text-gray-400"></i> Filter
                </button>
            </div>
        </div>

        <!-- KRS List Table -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead class="bg-[#F9FAFB]">
                        <tr>
                            <th scope="col"
                                class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                Mahasiswa
                            </th>
                            <th scope="col"
                                class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                Semester
                            </th>
                            <th scope="col"
                                class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Total
                                SKS
                            </th>
                            <th scope="col"
                                class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Tanggal
                                Pengajuan</th>
                            <th scope="col"
                                class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Status
                            </th>
                            <th scope="col"
                                class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-50">
                        @foreach($students as $student)
                            <tr class="hover:bg-gray-50 transition-colors group">
                                <td class="px-6 py-5 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div
                                            class="flex-shrink-0 h-10 w-10 rounded-full bg-gray-100 flex items-center justify-center text-gray-500 font-bold text-sm">
                                            {{ substr($student['name'], 0, 1) }}
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-bold text-gray-900">{{ $student['name'] }}</div>
                                            <div class="text-xs text-gray-500">{{ $student['nim'] }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap text-sm text-gray-600 font-medium">
                                    Semester {{ $student['semester'] }}
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap text-sm text-gray-600 font-medium">
                                    {{ $student['sks'] }} SKS
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap text-sm text-gray-600">
                                    {{ $student['date'] }}
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap">
                                    <span
                                        class="px-2.5 py-1 inline-flex text-xs leading-5 font-bold rounded-full bg-yellow-50 text-yellow-700 border border-yellow-100">
                                        <span class="w-1.5 h-1.5 rounded-full bg-yellow-500 mr-2 my-auto"></span> Waiting
                                        Approval
                                    </span>
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap text-center text-sm font-medium">
                                    <div class="flex items-center justify-center gap-2">
                                        <button
                                            class="text-green-600 hover:text-green-900 bg-green-50 hover:bg-green-100 p-2 rounded-lg transition-colors group-hover:shadow-sm"
                                            title="Setujui">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        <button
                                            class="text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 p-2 rounded-lg transition-colors group-hover:shadow-sm"
                                            title="Tolak">
                                            <i class="fas fa-times"></i>
                                        </button>
                                        <button
                                            class="text-blue-600 hover:text-blue-900 bg-blue-50 hover:bg-blue-100 p-2 rounded-lg transition-colors group-hover:shadow-sm"
                                            title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="bg-gray-50 px-6 py-4 border-t border-gray-100 flex items-center justify-between">
                <span class="text-sm text-gray-500">Menampilkan 1-5 dari 15 data</span>
                <div class="flex gap-2">
                    <button
                        class="px-3 py-1 text-sm rounded bg-white border border-gray-200 text-gray-600 disabled:opacity-50">Previous</button>
                    <button class="px-3 py-1 text-sm rounded bg-[#8B1538] text-white">1</button>
                    <button
                        class="px-3 py-1 text-sm rounded bg-white border border-gray-200 text-gray-600 hover:bg-gray-50">2</button>
                    <button
                        class="px-3 py-1 text-sm rounded bg-white border border-gray-200 text-gray-600 hover:bg-gray-50">3</button>
                    <button
                        class="px-3 py-1 text-sm rounded bg-white border border-gray-200 text-gray-600 hover:bg-gray-50">Next</button>
                </div>
            </div>
        </div>
    </div>
@endsection