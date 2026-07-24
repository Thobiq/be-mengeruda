<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Demographic extends Model
{
    use HasFactory;

    protected $fillable = [
        'total_penduduk', 'total_kk', 'laki_laki', 'perempuan',
        'dusun_data', 'pekerjaan_data', 'agama_data'
    ];

    protected $casts = [
        'dusun_data' => 'array',
        'pekerjaan_data' => 'array',
        'agama_data' => 'array',
    ];
}
