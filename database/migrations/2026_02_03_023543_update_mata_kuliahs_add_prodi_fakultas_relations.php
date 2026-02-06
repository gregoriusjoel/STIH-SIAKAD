<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Get existing fakultas
        $fakultas = \DB::table('fakultas')->first();
        if (!$fakultas) {
            $fakultasId = \DB::table('fakultas')->insertGetId([
                'kode_fakultas' => 'FH',
                'nama_fakultas' => 'Fakultas Hukum',
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now()
            ]);
        } else {
            $fakultasId = $fakultas->id;
        }

        // Create default prodi if not exists  
        $prodi = \DB::table('prodis')->first();
        if (!$prodi) {
            $prodiId = \DB::table('prodis')->insertGetId([
                'kode_prodi' => 'HK01',
                'nama_prodi' => 'Ilmu Hukum',
                'fakultas_id' => $fakultasId,
                'jenjang' => 'S1',
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now()
            ]);
        } else {
            $prodiId = $prodi->id;
        }

        // Update existing mata kuliah to have default prodi and fakultas
        // Ensure columns exist before updating rows
        if (!Schema::hasColumn('mata_kuliahs', 'prodi_id') || !Schema::hasColumn('mata_kuliahs', 'fakultas_id')) {
            Schema::table('mata_kuliahs', function (Blueprint $table) {
                if (!Schema::hasColumn('mata_kuliahs', 'prodi_id')) {
                    $table->unsignedBigInteger('prodi_id')->nullable();
                }
                if (!Schema::hasColumn('mata_kuliahs', 'fakultas_id')) {
                    $table->unsignedBigInteger('fakultas_id')->nullable();
                }
            });
        }

        \DB::table('mata_kuliahs')->when(Schema::hasColumn('mata_kuliahs', 'prodi_id'), function ($q) use ($prodiId) {
            return $q->where('prodi_id', 0)->orWhereNull('prodi_id')->update(['prodi_id' => $prodiId]);
        });

        \DB::table('mata_kuliahs')->when(Schema::hasColumn('mata_kuliahs', 'fakultas_id'), function ($q) use ($fakultasId) {
            return $q->whereNull('fakultas_id')->update(['fakultas_id' => $fakultasId]);
        });

        Schema::table('mata_kuliahs', function (Blueprint $table) {
            // Make fakultas_id not nullable
            $table->unsignedBigInteger('fakultas_id')->nullable(false)->change();
            
            // Add foreign key constraints if they don't exist
            try {
                $table->foreign('prodi_id')->references('id')->on('prodis')->onDelete('restrict');
            } catch (\Exception $e) {
                // Foreign key might already exist
            }
            
            try {
                $table->foreign('fakultas_id')->references('id')->on('fakultas')->onDelete('restrict');
            } catch (\Exception $e) {
                // Foreign key might already exist
            }
            
            // Drop old prodi string column if it exists
            if (Schema::hasColumn('mata_kuliahs', 'prodi')) {
                $table->dropColumn('prodi');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mata_kuliahs', function (Blueprint $table) {
            // Drop foreign keys
            $table->dropForeign(['prodi_id']);
            $table->dropForeign(['fakultas_id']);
            $table->dropColumn(['prodi_id', 'fakultas_id']);
            
            // Restore original prodi column
            $table->string('prodi');
        });
    }
};
