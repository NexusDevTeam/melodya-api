<?php

declare(strict_types=1);

namespace App\Repositories\Api\V1\Auth;

use App\Models\User;
use App\Repositories\BaseRepository;

class AuthRepository extends BaseRepository
{
    protected $model = User::class;

    public function userRegister()
    {
        $userId = auth()->id();
        if (User::whereNot('auth0_id', $userId)) {
            $profile = getAuth0UserProfile($userId);

            $user = User::create([
                'external_id' => \Str::uuid()->toString(),
                'auth0_id' => $profile['user_id'],
                'auth0_user_id' => $profile['identities'][0]['user_id'],
                'auth0_provider' => $profile['identities'][0]['provider'],
                'name' => $profile['nickname'],
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

        return response()->json([
            'message' => 'User already registered',
        ], 200);
    }

    public function userSync()
    {
        $userId = auth()->id();
        if ($user = User::where('auth0_id', $userId)) {
            $profile = getAuth0UserProfile($userId);

            $user->update([
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
            ], 201);
        }

        return response()->json([
            'message' => 'User not found',
        ], 404);
    }
}
