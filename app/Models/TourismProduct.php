<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TourismProduct extends Model
{
    use HasFactory;

    protected $table = 'tourism_umkm_products';

    protected $fillable = [
        'tourism_umkm_id',
        'name',
        'category',
        'price',
        'description',
        'images',
    ];

    protected $casts = [
        'images' => 'array',
    ];

    public function umkm()
    {
        return $this->belongsTo(TourismUmkm::class, 'tourism_umkm_id');
    }
}
