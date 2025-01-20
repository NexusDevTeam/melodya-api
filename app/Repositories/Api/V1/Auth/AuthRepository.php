<?php

declare(strict_types=1);

namespace App\Repositories\Api\V1\Auth;

use App\Models\User;
use Auth0\Laravel\UserRepositoryAbstract;
use Auth0\Laravel\UserRepositoryContract;
use Illuminate\Contracts\Auth\Authenticatable;

class AuthRepository extends UserRepositoryAbstract implements UserRepositoryContract
{
    protected $model = User::class;

    public function fromAccessToken(array $user): ?Authenticatable
    {
        return User::where('auth0_id', $user['sub'])->first();
    }

    public function fromSession(array $user): ?Authenticatable
    {
        $user = User::updateOrCreate(
            attributes: [
                'auth0_id' => $user['sub'],
            ],
            values: [
                'auth0_id' => $user['sub'],
                'auth0_user_id' => $user['identities']['0']['user_id'],
                'auth0_provider' => $user['identities']['0']['provider'],
            ]
        );

        return $user;
    }
}
