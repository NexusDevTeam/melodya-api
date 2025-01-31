<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * The base URL for the application.
     *
     * @var string
     */
    protected $baseUrl;

    /**
     * Register any application services.
     */
    public function register(): void
    {
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->baseUrl = env('APP_ENV') === 'local' ? env('APP_URL_FRONTEND_LOCAL') : env('APP_URL_FRONTEND');

        VerifyEmail::toMailUsing(function (object $notifiable, string $url) {
            $urlPath = parse_url($url, PHP_URL_PATH);
            $queryString = parse_url($url, PHP_URL_QUERY);

            $identifier = str_replace('/api/email/verify/', '', $urlPath);
            [$userId, $hash] = explode('/', $identifier);

            $finalUrl = "{$this->baseUrl}/auth/verify-email?user={$userId}&hash={$hash}&{$queryString}";

            return (new MailMessage())
                ->subject('Email de Verificação de Conta')
                ->view('emails.verify-email', [
                    'user' => $notifiable,
                    'url' => $finalUrl,
                ]);
        });
    }
}
