<?php

namespace App\Services\Api\V1\Auth0;

use Auth0\Laravel\Facade\Auth0;

class Auth0Service
{
    /**
     * Obtém o perfil do usuário a partir do cache ou via API Auth0.
     *
     * @return array
     */
    public function getUserProfile(string $userId)
    {
        $profile = cache()->get($userId);

        if ($profile === null) {
            $endpoint = Auth0::management()->users();
            $profile = $endpoint->get($userId);
            $profile = Auth0::json($profile);

            cache()->put($userId, $profile, 120);
        }

        return $profile;
    }
}
