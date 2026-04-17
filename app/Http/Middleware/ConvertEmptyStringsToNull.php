<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ConvertEmptyStringsToNull
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Convert empty strings to null for nullable fields
        $nullableFields = [
            'jenis_kelamin',
            'agama',
            'status_sipil',
            'alamat',
            'tempat_lahir',
            'tanggal_lahir',
            'rt',
            'rw',
            'kota',
            'kecamatan',
            'desa',
            'provinsi',
            'alamat_ktp',
            'rt_ktp',
            'rw_ktp',
            'provinsi_ktp',
            'kota_ktp',
            'kecamatan_ktp',
            'desa_ktp',
        ];

        foreach ($nullableFields as $field) {
            if ($request->has($field) && $request->input($field) === '') {
                $request->merge([$field => null]);
            }
        }

        return $next($request);
    }
}
