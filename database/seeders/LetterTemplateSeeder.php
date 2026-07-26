<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LetterTemplate;

class LetterTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $templates = [
            [
                'name' => 'Surat Keterangan Domisili',
                'description' => 'Surat keterangan resmi bahwa warga yang bersangkutan berdomisili di Desa Mengeruda.',
                'required_fields' => [
                    ['name' => 'alamat_asal', 'label' => 'Alamat Asal (Sesuai KTP)', 'type' => 'text', 'required' => true],
                    ['name' => 'alamat_domisili', 'label' => 'Alamat Domisili Sekarang', 'type' => 'text', 'required' => true],
                    ['name' => 'keperluan', 'label' => 'Keperluan Surat', 'type' => 'text', 'required' => true],
                ],
            ],
            [
                'name' => 'Surat Keterangan Usaha (SKU)',
                'description' => 'Surat pengantar atau keterangan kepemilikan usaha bagi warga Desa Mengeruda.',
                'required_fields' => [
                    ['name' => 'nama_usaha', 'label' => 'Nama Usaha / Toko', 'type' => 'text', 'required' => true],
                    ['name' => 'bidang_usaha', 'label' => 'Bidang Usaha (e.g. Kuliner, Pertanian, Kerajinan)', 'type' => 'text', 'required' => true],
                    ['name' => 'alamat_usaha', 'label' => 'Alamat Tempat Usaha', 'type' => 'text', 'required' => true],
                    ['name' => 'keperluan', 'label' => 'Keperluan Pembuatan SKU', 'type' => 'text', 'required' => true],
                ],
            ],
            [
                'name' => 'Surat Keterangan Tidak Mampu (SKTM)',
                'description' => 'Surat keterangan untuk keperluan keringanan biaya pendidikan, pengobatan, atau bantuan sosial.',
                'required_fields' => [
                    ['name' => 'pekerjaan', 'label' => 'Pekerjaan Utama', 'type' => 'text', 'required' => true],
                    ['name' => 'penghasilan', 'label' => 'Rata-rata Penghasilan per Bulan', 'type' => 'text', 'required' => true],
                    ['name' => 'tanggungan', 'label' => 'Jumlah Anggota Keluarga / Tanggungan', 'type' => 'number', 'required' => true],
                    ['name' => 'keperluan', 'label' => 'Keperluan Pengajuan SKTM', 'type' => 'text', 'required' => true],
                ],
            ],
        ];

        foreach ($templates as $t) {
            LetterTemplate::firstOrCreate(
                ['name' => $t['name']],
                [
                    'description' => $t['description'],
                    'required_fields' => $t['required_fields'],
                ]
            );
        }
    }
}
