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
        Schema::create('demographics', function (Blueprint $table) {
            $table->id();
            
            // Penduduk Umum
            $table->integer('total_penduduk')->default(0);
            $table->integer('total_kk')->default(0);
            $table->integer('laki_laki')->default(0);
            $table->integer('perempuan')->default(0);

            // Persebaran Dusun
            $table->integer('dusun_tengah_tiba')->default(0);
            $table->integer('dusun_nusa_nuba')->default(0);
            $table->integer('dusun_tangi_watu')->default(0);
            $table->integer('dusun_nunu_bolo')->default(0);

            // Pekerjaan (16 kategori)
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

            // Agama
            $table->integer('agama_islam')->default(0);
            $table->integer('agama_kristen')->default(0);
            $table->integer('agama_katolik')->default(0);
            $table->integer('agama_hindu')->default(0);
            $table->integer('agama_budha')->default(0);
            $table->integer('agama_konghucu')->default(0);
            $table->integer('agama_lainnya')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('demographics');
    }
};
