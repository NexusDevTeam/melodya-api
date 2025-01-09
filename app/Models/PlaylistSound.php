<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlaylistSound extends Model
{
    use HasFactory;

    protected $fillable = [
        'playlist_id',
        'sound_id',
    ];
}
