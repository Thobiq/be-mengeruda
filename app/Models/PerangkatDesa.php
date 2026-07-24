<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PerangkatDesa extends Model
{
    protected $fillable = [
        'nama',
        'jabatan',
        'foto',
        'parent_id',
        'urutan',
    ];

    // Relasi ke atasan (parent)
    public function parent()
    {
        return $this->belongsTo(PerangkatDesa::class, 'parent_id');
    }

    // Relasi ke bawahan (children)
    public function children()
    {
        return $this->hasMany(PerangkatDesa::class, 'parent_id')->orderBy('urutan');
    }
}
