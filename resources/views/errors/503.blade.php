<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Sedang Dalam Pemeliharaan - 503</title>

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
                <i class="fas fa-tools text-4xl text-maroon"></i>
            </div>

            <h1 class="text-3xl font-bold text-gray-800 mb-2">Pemeliharaan Sistem</h1>
            <h2 class="text-xl font-semibold text-gray-600 mb-4">503 - Service Unavailable</h2>

            <p class="text-gray-500 mb-8">
                Website sedang dalam pemeliharaan rutin untuk meningkatkan layanan kami.
                Silakan kembali lagi dalam beberapa saat.
            </p>

            <div class="space-y-4">
                <button onclick="location.reload()"
                    class="inline-block w-full py-3 px-6 bg-maroon hover:bg-maroon-hover text-white font-semibold rounded-lg shadow-md transition-colors duration-200">
                    <i class="fas fa-sync-alt mr-2"></i> Cek Status Lagi
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