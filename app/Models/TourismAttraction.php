<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TourismAttraction extends Model
{
    use HasFactory;

    protected $table = 'tourism_attractions';

    protected $fillable = [
        'name',
        'address',
        'price',
        'description',
        'image',
    ];
}
