<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VillageProfile extends Model
{
    protected $fillable = [
        'logo',
        'nama_desa',
        'kecamatan',
        'kabupaten',
        'provinsi',
        'telp',
        'email',
        'alamat',
        'tentang_desa',
        'sejarah_desa',
        'visi_desa',
        'misi_desa',
    ];
}
