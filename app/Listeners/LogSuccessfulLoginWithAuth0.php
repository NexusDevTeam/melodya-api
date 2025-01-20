<?php

namespace App\Listeners;

use App\Models\User;
use Auth0\Laravel\Events\AuthenticationSucceeded;

final class LogSuccessfulLoginWithAuth0
{
    public function handle(AuthenticationSucceeded $event): void
    {
        $user = $event->user;

        // Atualiza ou cria o usuário com base no ID do Auth0
        User::updateOrCreate(
            [
                'auth0_id' => $user['sub'], // O ID do usuário vindo do Auth0
            ],
            [
                'auth0_user_id' => $user['identities'][0]['user_id'],
                'auth0_provider' => $user['identities'][0]['provider'],
            ]
        );
    }
}
