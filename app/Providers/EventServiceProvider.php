<?php

namespace App\Providers;

use App\Listeners\LogSuccessfulLoginWithAuth0;
use Auth0\Laravel\Events\AuthenticationSucceeded;
use Carbon\Laravel\ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Event::listen(
            AuthenticationSucceeded::class, // Evento disparado quando a autenticação via Auth0 for bem-sucedida
            [LogSuccessfulLoginWithAuth0::class, 'handle'] // Listener que vai tratar a lógica
        );
    }
}
