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
        // 1. Tempat Wisata
        Schema::create('tourism_attractions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('address')->nullable();
            $table->string('price')->default('Gratis');
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->timestamps();
        });

        // 2. UMKM (Toko)
        Schema::create('tourism_umkms', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('address')->nullable();
            $table->string('gmaps')->nullable();
            $table->string('wa')->nullable();
            $table->string('ig')->nullable();
            $table->string('fb')->nullable();
            $table->string('tiktok')->nullable();
            $table->string('logo')->nullable();
            $table->timestamps();
        });

        // 3. Produk UMKM
        Schema::create('tourism_umkm_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tourism_umkm_id')->constrained('tourism_umkms')->onDelete('cascade');
            $table->string('name');
            $table->string('category')->default('Makanan');
            $table->string('price')->nullable();
            $table->text('description')->nullable();
            $table->json('images')->nullable();
            $table->timestamps();
        });

        // 4. Berita Pariwisata
        Schema::create('tourism_news', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->date('date');
            $table->string('author')->default('Admin Desa');
            $table->longText('content')->nullable();
            $table->string('status')->default('Diterbitkan');
            $table->string('image')->nullable();
            $table->timestamps();
        });

        // 5. Galeri Pariwisata
        Schema::create('tourism_galleries', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('category')->default('Alam');
            $table->string('image')->nullable();
            $table->timestamps();
        });

        // 6. Agenda / Kegiatan Pariwisata
        Schema::create('tourism_events', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->date('date');
            $table->string('location')->nullable();
            $table->string('status')->default('Akan Datang');
            $table->string('image')->nullable();
            $table->timestamps();
        });

        // 7. Profil Pariwisata
        Schema::create('tourism_profiles', function (Blueprint $table) {
            $table->id();
            $table->string('nama_desa')->default('Desa Mengeruda');
            $table->text('deskripsi_singkat')->nullable();
            $table->longText('sejarah')->nullable();
            $table->string('telepon')->nullable();
            $table->string('email')->nullable();
            $table->string('alamat')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tourism_profiles');
        Schema::dropIfExists('tourism_events');
        Schema::dropIfExists('tourism_galleries');
        Schema::dropIfExists('tourism_news');
        Schema::dropIfExists('tourism_umkm_products');
        Schema::dropIfExists('tourism_umkms');
        Schema::dropIfExists('tourism_attractions');
    }
};
