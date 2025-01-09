<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sound extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'sound_url',
        'number_reproductions',
        'duration_seconds',
        'credits',
        'album_id',
    ];
}
