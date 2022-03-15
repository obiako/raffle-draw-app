<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RaffleDraw extends Model
{
    use HasFactory;
    protected $fillable = ['winners'];
    protected $casts = [
        'winners' => 'array',
    ];
}
