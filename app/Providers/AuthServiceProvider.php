<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Notifications\Messages\MailMessage;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Boot the authentication services for the application.
     *
     * @return void
     */
    public function boot()
    {
        // Here you may define how you wish users to be authenticated for your Lumen
        // application. The callback which receives the incoming request instance
        // should return either a User instance or null. You're free to obtain
        // the User instance via an API token or any other method necessary.

        $this->app['auth']->viaRequest('api', function ($request) {

            if ($appLocale = $request->header('LANG') ?? $request->get('lang')) {
                if (in_array($appLocale, config('locales.locales'))) {
                    $request->request->add(['lang' => $appLocale]);
                }
            }

            if ($api_token = $request->header('Authorization')) {

                $user = User::where('api_token', $api_token)->first();
                if (!empty($user)) $request->request->add(['user_id' => $user->id]);

                return $user;
            }

        });
    }
}
