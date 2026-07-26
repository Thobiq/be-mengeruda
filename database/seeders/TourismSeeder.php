<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TourismAttraction;
use App\Models\TourismUmkm;
use App\Models\TourismProduct;
use App\Models\TourismNews;
use App\Models\TourismGallery;
use App\Models\TourismEvent;
use App\Models\TourismProfile;

class TourismSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Tempat Wisata
        if (TourismAttraction::count() === 0) {
            TourismAttraction::create([
                'name' => 'Pemandian Air Panas Mengeruda',
                'address' => 'Kawasan Inti Desa Mengeruda, Soa, Ngada',
                'price' => 'Rp 10.000 (Domestik) / Rp 50.000 (Asing)',
                'description' => 'Sumber mata air panas alami bersuhu hangat konstan dengan kolam berundak yang indah serta aliran air terjun belerang yang menyehatkan kulit.',
                'image' => '/hero-1.jpg',
            ]);

            TourismAttraction::create([
                'name' => 'Situs Batu Megalitik Mengeruda',
                'address' => 'Utara Kawasan Air Panas Mengeruda',
                'price' => 'Gratis',
                'description' => 'Kawasan bersejarah peninggalan leluhur suku setempat berupa struktur batu megalitik dan menhir kuno.',
                'image' => '/hero-1.jpg',
            ]);

            TourismAttraction::create([
                'name' => 'Bukit Sabana Soa',
                'address' => 'Jalur Perbukitan Soa Mengeruda',
                'price' => 'Rp 5.000',
                'description' => 'Bukit sabana hijau dengan pemandangan pegunungan Inerie dan padang rumput khas daratan Ngada.',
                'image' => '/hero-1.jpg',
            ]);
        }

        // 2. UMKM & Produk
        if (TourismUmkm::count() === 0) {
            $umkm1 = TourismUmkm::create([
                'name' => 'Kopi Tradisional Bajawa Mengeruda',
                'address' => 'Jl. Raya Soa, Mengeruda, Ngada',
                'gmaps' => 'https://maps.google.com/?q=Mengeruda',
                'wa' => '081234567890',
                'ig' => '@kopibajawa_mengeruda',
                'fb' => 'Kopi Bajawa Mengeruda',
                'tiktok' => '@kopibajawaofficial',
                'logo' => '/hero-1.jpg',
            ]);

            TourismProduct::create([
                'tourism_umkm_id' => $umkm1->id,
                'name' => 'Kopi Susu Gula Aren Soa',
                'category' => 'Minuman',
                'price' => 'Rp 20.000',
                'description' => 'Kopi robusta dan arabika asli pilihan khas dataran tinggi Ngada dipadukan gula aren aren organik.',
                'images' => ['/hero-1.jpg'],
            ]);

            TourismProduct::create([
                'tourism_umkm_id' => $umkm1->id,
                'name' => 'Biji Kopi Arabika Bajawa (250g)',
                'category' => 'Oleh-oleh',
                'price' => 'Rp 65.000',
                'description' => 'Biji kopi arabika Bajawa kualitas ekspor dengan aroma floral dan karamel khas.',
                'images' => ['/hero-1.jpg'],
            ]);

            $umkm2 = TourismUmkm::create([
                'name' => 'Tenun Ikat Flores Mengeruda',
                'address' => 'Pasar Seni Kawasan Wisata Mengeruda',
                'gmaps' => '',
                'wa' => '089876543210',
                'ig' => '@tenunmengeruda',
                'fb' => 'Tenun Ikat Flores Mengeruda',
                'tiktok' => '',
                'logo' => '/hero-1.jpg',
            ]);

            TourismProduct::create([
                'tourism_umkm_id' => $umkm2->id,
                'name' => 'Selendang Tenun Ikat Motif Soa',
                'category' => 'Kriya',
                'price' => 'Rp 150.000',
                'description' => 'Selendang tenun ikat tradisional buatan tangan pengrajin wanita Desa Mengeruda.',
                'images' => ['/hero-1.jpg'],
            ]);

            TourismProduct::create([
                'tourism_umkm_id' => $umkm2->id,
                'name' => 'Sarung Adat Ngada Asli',
                'category' => 'Oleh-oleh',
                'price' => 'Rp 750.000',
                'description' => 'Sarung tenun pewarna alami yang biasa digunakan dalam upacara adat Reba.',
                'images' => ['/hero-1.jpg'],
            ]);
        }

        // 3. Berita Pariwisata
        if (TourismNews::count() === 0) {
            TourismNews::create([
                'title' => 'Festival Reba Mengeruda Siap Menyambut Wisatawan',
                'slug' => 'festival-reba-mengeruda-siap-menyambut-wisatawan',
                'date' => '2026-04-28',
                'author' => 'Dinas Pariwisata',
                'content' => '<p>Festival adat perayaan Reba tahun ini akan diselenggarakan dengan meriah di kawasan desa adat Mengeruda. Wisatawan diundang untuk menyaksikan pertunjukan tarian adat dan ritual suci.</p>',
                'status' => 'Diterbitkan',
                'image' => '/hero-1.jpg',
            ]);

            TourismNews::create([
                'title' => 'Revitalisasi Fasilitas Pemandian Air Panas Mengeruda',
                'slug' => 'revitalisasi-fasilitas-pemandian-air-panas-mengeruda',
                'date' => '2026-04-15',
                'author' => 'Admin Desa',
                'content' => '<p>Pemerintah Desa Mengeruda telah menyelesaikan renovasi kolam renang anak dan area gazebo relaksasi untuk kenyamanan pengunjung.</p>',
                'status' => 'Diterbitkan',
                'image' => '/hero-1.jpg',
            ]);
        }

        // 4. Galeri
        if (TourismGallery::count() === 0) {
            TourismGallery::create([
                'title' => 'Keindahan Mata Air Panas Mengeruda',
                'category' => 'Alam',
                'image' => '/hero-1.jpg',
            ]);
            TourismGallery::create([
                'title' => 'Tarian Adat Penyambutan Tamu',
                'category' => 'Budaya',
                'image' => '/hero-1.jpg',
            ]);
            TourismGallery::create([
                'title' => 'Kopi Tradisional Bajawa',
                'category' => 'Kuliner',
                'image' => '/hero-1.jpg',
            ]);
            TourismGallery::create([
                'title' => 'Fasilitas Kolam Renang Keluarga',
                'category' => 'Fasilitas',
                'image' => '/hero-1.jpg',
            ]);
        }

        // 5. Kegiatan / Agenda
        if (TourismEvent::count() === 0) {
            TourismEvent::create([
                'name' => 'Festival Reba',
                'date' => '2026-04-28',
                'location' => 'Pusat Desa Mengeruda',
                'description' => 'Festival Reba merupakan perayaan tahun baru adat masyarakat Ngada sebagai bentuk penghormatan tertinggi kepada para leluhur dan ungkapan rasa syukur atas kelimpahan panen hasil bumi. Perayaan sakral ini ditandai dengan tradisi makan ubi bersama, tarian massal yang diiringi musik tradisional, serta ritual penyucian adat yang mengikat kebersamaan antar-warga desa dan wisatawan yang berkunjung.',
                'status' => 'Akan Datang',
                'image' => '/hero-1.jpg',
            ]);
            TourismEvent::create([
                'name' => 'Upacara Adat Penti',
                'date' => '2026-08-15',
                'location' => 'Rumah Adat Soa',
                'description' => 'Upacara Adat Penti adalah perhelatan agung tahunan yang dilaksanakan di kawasan Rumah Adat Soa, Mengeruda. Upacara ini menjadi momen spiritual untuk memohon perlindungan, ketenteraman, dan kesuburan bagi tanah adat dan mata air panas alami. Wisatawan diundang untuk menyaksikan kemegahan busana adat khas Ngada, tarian Ja\'i bersama, serta prosesi syukuran yang syarat akan nilai luhur.',
                'status' => 'Direncanakan',
                'image' => '/hero-1.jpg',
            ]);
        }

        // 6. Profil Desa Pariwisata
        if (TourismProfile::count() === 0) {
            TourismProfile::create([
                'nama_desa' => 'Desa Mengeruda',
                'deskripsi_singkat' => 'Menjelajahi keajaiban geo-wisata dan kekayaan budaya peninggalan leluhur di tanah Ngada.',
                'sejarah' => 'Mengeruda merupakan kawasan geo-wisata yang terkenal dengan kolam pemandian air panas alami dan penemuan batuan purba. Desa ini telah menjadi titik kumpul masyarakat lokal dalam merayakan hasil bumi dan kekayaan adat.',
                'telepon' => '081234567890',
                'email' => 'pariwisata@mengeruda.id',
                'alamat' => 'Kantor Desa Mengeruda, Kec. Soa, Kab. Ngada, Nusa Tenggara Timur',
            ]);
        }
    }
}
