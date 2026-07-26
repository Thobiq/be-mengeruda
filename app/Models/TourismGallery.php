<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TourismGallery extends Model
{
    use HasFactory;

    protected $table = 'tourism_galleries';

    protected $fillable = [
        'title',
        'category',
        'image',
    ];
}
