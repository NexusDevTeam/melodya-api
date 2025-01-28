<?php

use App\Services\Api\V1\Auth0\Auth0Service;

if (!function_exists('getAuth0UserProfile')) {
    /**
     * Obtém o perfil do usuário do Auth0 e armazena no cache.
     */
    function getAuth0UserProfile(string $userId): ?array
    {
        $auth0Service = app(Auth0Service::class);

        return $auth0Service->getUserProfile($userId);
    }
}
