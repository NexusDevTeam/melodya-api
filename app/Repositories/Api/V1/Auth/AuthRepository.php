<?php

declare(strict_types=1);

namespace App\Repositories\Api\V1\Auth;

use App\Models\User;
use App\Repositories\BaseRepository;
use Auth0\Laravel\Facade\Auth0;

class AuthRepository extends BaseRepository
{
    protected $model = User::class;

    public function userRegister()
    {
        $user = auth()->id();
        $profile = cache()->get($user);

        if (null === $profile) {
            $endpoint = Auth0::management()->users();
            $profile = $endpoint->get($user);
            $profile = Auth0::json($profile);

            cache()->put($user, $profile, 120);
        }

        User::create([
            'auth0_id' => $profile['user_id'],
            'auth0_user_id' => $profile['identities'][0]['user_id'],
            'auth0_provider' => $profile['identities'][0]['provider'],
            'name' => $profile['name'],
            'email' => $profile['email'],
            'avatar_auth0_url' => $profile['picture'],
            'avatar_url' => $profile['picture'],
            'email_verified' => $profile['email_verified'],
            'is_social' => $profile['identities'][0]['isSocial'],
            'created_at' => $profile['created_at'],
            'updated_at' => $profile['updated_at'],
        ]);

        return response()->json([
            'message' => 'User registered successfully',
            'user' => $profile,
        ], 201);
    }

    public function userSync()
    {
        $user = auth()->id();
        $profile = cache()->get($user);

        if (null === $profile) {
            $endpoint = Auth0::management()->users();
            $profile = $endpoint->get($user);
            $profile = Auth0::json($profile);

            cache()->put($user, $profile, 120);
        }

        User::where('auth0_id', $profile['user_id'])->update([
            'auth0_user_id' => $profile['identities'][0]['user_id'],
            'auth0_provider' => $profile['identities'][0]['provider'],
            'email' => $profile['email'],
            'avatar_auth0_url' => $profile['picture'],
            'email_verified' => $profile['email_verified'],
            'is_social' => $profile['identities'][0]['isSocial'],
            'updated_at' => $profile['updated_at'],
        ]);

        return response()->json([
            'message' => 'User successfully synchronized',
            'user' => $profile,
        ], 204);
    }
}
