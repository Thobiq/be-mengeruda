<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TourismEvent extends Model
{
    use HasFactory;

    protected $table = 'tourism_events';

    protected $fillable = [
        'name',
        'date',
        'location',
        'status',
        'image',
    ];
}
