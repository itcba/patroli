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
    // Keep raw filename hidden, but expose a full public URL via accessor
    protected $hidden = ['esign_image'];

    // Append computed attributes to model's array / JSON form
    protected $appends = ['esign_image_url'];

    public function getEsignImageUrlAttribute()
    {
        if (! $this->esign_image) {
            return null;
        }

        // If the DB already contains a data URL (legacy entries storing base64), return it directly
        if (str_starts_with($this->esign_image, 'data:')) {
            return $this->esign_image;
        }

        // If the stored value looks like a full URL or storage path, return as-is
        if (str_starts_with($this->esign_image, 'http') || str_contains($this->esign_image, 'storage/')) {
            return $this->esign_image;
        }

        // Otherwise assume it's a filename saved under storage/app/public/signatures
        return asset('storage/signatures/' . $this->esign_image);
    }
}