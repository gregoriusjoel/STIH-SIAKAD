<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Akses Ditolak - 403</title>

    <!-- Fonts -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-50 flex items-center justify-center min-h-screen p-6">

    <div
        class="max-w-md w-full bg-white rounded-2xl shadow-xl overflow-hidden text-center transform hover:scale-105 transition-transform duration-300">
        <div class="p-8">
            <div class="w-20 h-20 bg-red-50 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-ban text-4xl text-maroon"></i>
            </div>

            <h1 class="text-3xl font-bold text-gray-800 mb-2">Akses Ditolak</h1>
            <h2 class="text-xl font-semibold text-gray-600 mb-4">403 - Forbidden</h2>

            <p class="text-gray-500 mb-8">
                Maaf, Anda tidak memiliki izin untuk mengakses halaman ini.
            </p>

            <div class="space-y-4">
                <a href="{{ url('/') }}"
                    class="inline-block w-full py-3 px-6 bg-maroon hover:bg-maroon-hover text-white font-semibold rounded-lg shadow-md transition-colors duration-200">
                    <i class="fas fa-home mr-2"></i> Kembali ke Beranda
                </a>

                <button onclick="history.back()"
                    class="inline-block w-full py-3 px-6 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 font-semibold rounded-lg transition-colors duration-200">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali
                </button>
            </div>
        </div>
        <div class="bg-gray-50 px-6 py-4 border-t border-gray-100">
            <p class="text-xs text-center text-gray-400">
                &copy; {{ date('Y') }} SIAKAD STIH. All rights reserved.
            </p>
        </div>
    </div>

</body>

</html>