<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Playlist extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = ['id'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function sounds() {
        return $this->hasMany(Sound::class);
    }

    public function favorites() {
        return $this->morphMany(Favorite::class, 'favoriteable');
    }
}
