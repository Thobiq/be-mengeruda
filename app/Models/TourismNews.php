<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TourismNews extends Model
{
    use HasFactory;

    protected $table = 'tourism_news';

    protected $fillable = [
        'title',
        'slug',
        'date',
        'author',
        'content',
        'status',
        'image',
    ];
}
