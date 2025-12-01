<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patrol extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'patrol_details' => 'array', // Konversi otomatis JSON ke Array
    ];
}