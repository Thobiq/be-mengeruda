<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TourismProfile extends Model
{
    use HasFactory;

    protected $table = 'tourism_profiles';

    protected $fillable = [
        'nama_desa',
        'deskripsi_singkat',
        'sejarah',
        'telepon',
        'email',
        'alamat',
    ];
}
