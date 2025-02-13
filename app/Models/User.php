<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\ActiveRoleUser;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements Auditable, CanResetPassword, MustVerifyEmail
{
    use \OwenIt\Auditing\Auditable;

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use HasRoles;
    use SoftDeletes;

    protected $guarded = ['id'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public static function findByExternalId($externalId)
    {
        return self::where('external_id', $externalId)->first();
    }

    public function playlists()
    {
        return $this->hasMany(Playlist::class);
    }

    public function albums()
    {
        return $this->hasMany(Album::class);
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function artist()
    {
        return $this->hasOne(Artist::class);
    }

    public function following(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'follows', 'following_user_id', 'followed_user_id');
    }

    public function followers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'follows', 'followed_user_id', 'following_user_id');
    }

    public function follow(User $user)
    {
        return $this->following()->attach($user->id);
    }

    public function unfollow(User $user)
    {
        return $this->following()->detach($user->id);
    }

    public function isFollowing(User $user)
    {
        return $this->following()->where('followed_user_id', $user->id)->exists();
    }

    public function isFavorite($model)
    {
        return $this->favorites()
            ->where('favoritable_id', $model->id)
            ->where('favoritable_type', get_class($model))
            ->exists();
    }

    public function historicSounds()
    {
        return $this->belongsToMany(Sound::class, 'music_plays', 'user_id', 'sound_id')
                    ->withTimestamps();
    }

    public function isAdministrator()
    {
        return ActiveRoleUser::admin($this->id);
    }
}
