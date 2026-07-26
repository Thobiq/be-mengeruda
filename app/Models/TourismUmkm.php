<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TourismUmkm extends Model
{
    use HasFactory;

    protected $table = 'tourism_umkms';

    protected $fillable = [
        'name',
        'address',
        'gmaps',
        'wa',
        'ig',
        'fb',
        'tiktok',
        'logo',
    ];

    public function products()
    {
        return $this->hasMany(TourismProduct::class, 'tourism_umkm_id');
    }
}
