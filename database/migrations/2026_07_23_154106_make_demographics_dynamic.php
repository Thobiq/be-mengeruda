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
        Schema::table('demographics', function (Blueprint $table) {
            // Drop old columns
            $table->dropColumn([
                'dusun_tengah_tiba', 'dusun_nusa_nuba', 'dusun_tangi_watu', 'dusun_nunu_bolo',
                'pekerjaan_pns', 'pekerjaan_tni_polri', 'pekerjaan_wiraswasta', 'pekerjaan_petani', 
                'pekerjaan_tukang', 'pekerjaan_pelajar', 'pekerjaan_transportasi', 'pekerjaan_tukang_cukur', 
                'pekerjaan_irt', 'pekerjaan_mekanik', 'pekerjaan_dosen', 'pekerjaan_guru', 
                'pekerjaan_bidan', 'pekerjaan_perangkat_desa', 'pekerjaan_pensiunan', 'pekerjaan_belum_bekerja',
                'agama_islam', 'agama_kristen', 'agama_katolik', 'agama_hindu', 
                'agama_budha', 'agama_konghucu', 'agama_lainnya'
            ]);

            // Add new JSON columns
            $table->json('dusun_data')->nullable();
            $table->json('pekerjaan_data')->nullable();
            $table->json('agama_data')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('demographics', function (Blueprint $table) {
            $table->dropColumn(['dusun_data', 'pekerjaan_data', 'agama_data']);
            
            // Re-add old columns
            $table->integer('dusun_tengah_tiba')->default(0);
            $table->integer('dusun_nusa_nuba')->default(0);
            $table->integer('dusun_tangi_watu')->default(0);
            $table->integer('dusun_nunu_bolo')->default(0);
            
            $table->integer('pekerjaan_pns')->default(0);
            $table->integer('pekerjaan_tni_polri')->default(0);
            $table->integer('pekerjaan_wiraswasta')->default(0);
            $table->integer('pekerjaan_petani')->default(0);
            $table->integer('pekerjaan_tukang')->default(0);
            $table->integer('pekerjaan_pelajar')->default(0);
            $table->integer('pekerjaan_transportasi')->default(0);
            $table->integer('pekerjaan_tukang_cukur')->default(0);
            $table->integer('pekerjaan_irt')->default(0);
            $table->integer('pekerjaan_mekanik')->default(0);
            $table->integer('pekerjaan_dosen')->default(0);
            $table->integer('pekerjaan_guru')->default(0);
            $table->integer('pekerjaan_bidan')->default(0);
            $table->integer('pekerjaan_perangkat_desa')->default(0);
            $table->integer('pekerjaan_pensiunan')->default(0);
            $table->integer('pekerjaan_belum_bekerja')->default(0);
            
            $table->integer('agama_islam')->default(0);
            $table->integer('agama_kristen')->default(0);
            $table->integer('agama_katolik')->default(0);
            $table->integer('agama_hindu')->default(0);
            $table->integer('agama_budha')->default(0);
            $table->integer('agama_konghucu')->default(0);
            $table->integer('agama_lainnya')->default(0);
        });
    }
};
