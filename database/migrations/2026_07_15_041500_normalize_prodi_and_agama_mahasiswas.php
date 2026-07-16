<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tambah kolom agama_id di tabel mahasiswas
        Schema::table('mahasiswas', function (Blueprint $table) {
            if (!Schema::hasColumn('mahasiswas', 'agama_id')) {
                $table->unsignedBigInteger('agama_id')->nullable()->after('agama');
            }
        });

        // 2. Seeding prodi yang belum terdaftar di tabel prodis
        if (Schema::hasColumn('mahasiswas', 'prodi')) {
            $missingProdis = DB::table('mahasiswas')
                ->select('prodi')
                ->whereNotNull('prodi')
                ->where('prodi', '!=', '')
                ->distinct()
                ->get();

            foreach ($missingProdis as $mp) {
                $exists = DB::table('prodis')
                    ->whereRaw('LOWER(TRIM(nama_prodi)) = ?', [strtolower(trim($mp->prodi))])
                    ->exists();

                if (!$exists) {
                    $kode = strtoupper(substr(str_replace(' ', '', $mp->prodi), 0, 3));
                    // Pastikan kode_prodi unik
                    $suffix = 1;
                    $origKode = $kode;
                    while (DB::table('prodis')->where('kode_prodi', $kode)->exists()) {
                        $kode = $origKode . $suffix++;
                    }

                    DB::table('prodis')->insert([
                        'kode_prodi' => $kode,
                        'nama_prodi' => $mp->prodi,
                        'jenjang' => 'S1',
                        'status' => 'aktif',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            // 3. Mapping data prodi -> prodi_id
            $prodis = DB::table('prodis')->get();
            foreach ($prodis as $prodi) {
                DB::table('mahasiswas')
                    ->whereRaw('LOWER(TRIM(prodi)) = ?', [strtolower(trim($prodi->nama_prodi))])
                    ->update(['prodi_id' => $prodi->id]);
            }
        }

        // 4. Mapping data agama -> agama_id
        if (Schema::hasColumn('mahasiswas', 'agama')) {
            $religions = DB::table('religions')->get();
            foreach ($religions as $rel) {
                DB::table('mahasiswas')
                    ->whereRaw('LOWER(TRIM(agama)) = ?', [strtolower(trim($rel->name))])
                    ->update(['agama_id' => $rel->id]);
            }
        }

        // 5. Tambah foreign key constraint untuk agama_id
        Schema::table('mahasiswas', function (Blueprint $table) {
            $table->foreign('agama_id')->references('id')->on('religions')->onDelete('set null');
        });

        // 6. Drop kolom prodi dan agama (varchar)
        Schema::table('mahasiswas', function (Blueprint $table) {
            if (Schema::hasColumn('mahasiswas', 'prodi')) {
                $table->dropColumn('prodi');
            }
            if (Schema::hasColumn('mahasiswas', 'agama')) {
                $table->dropColumn('agama');
            }
        });
    }

    public function down(): void
    {
        // 1. Kembalikan kolom prodi dan agama
        Schema::table('mahasiswas', function (Blueprint $table) {
            $table->string('prodi')->nullable()->after('prodi_id');
            $table->string('agama')->nullable()->after('agama_id');
        });

        // 2. Kembalikan data dari ID ke string
        $prodis = DB::table('prodis')->get();
        foreach ($prodis as $prodi) {
            DB::table('mahasiswas')
                ->where('prodi_id', $prodi->id)
                ->update(['prodi' => $prodi->nama_prodi]);
        }

        $religions = DB::table('religions')->get();
        foreach ($religions as $rel) {
            DB::table('mahasiswas')
                ->where('agama_id', $rel->id)
                ->update(['agama' => $rel->name]);
        }

        // 3. Drop foreign key dan kolom agama_id
        Schema::table('mahasiswas', function (Blueprint $table) {
            $table->dropForeign(['agama_id']);
            $table->dropColumn('agama_id');
        });
    }
};
