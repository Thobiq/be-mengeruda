<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApbDesa extends Model
{
    protected $fillable = ['year', 'data'];

    protected $casts = [
        'data' => 'array',
    ];
}
