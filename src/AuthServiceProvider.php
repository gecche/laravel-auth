<?php

namespace Gecche\Auth;

use Gecche\Auth\Console\ClearVerificationsCommand;
use Gecche\Auth\Contracts\Authenticatable as AuthenticatableContract;
use Gecche\Auth\Console\AuthMakeCommand;

class AuthServiceProvider extends \Illuminate\Auth\AuthServiceProvider
{

    /**
     * Register the authenticator services.
     *
     * @return void
     */
    protected function registerAuthenticator()
    {
        $this->app->singleton('auth', function ($app) {
            // Once the authentication service has actually been requested by the developer
            // we will set a variable in the application indicating such. This helps us
            // know that we need to set any queued cookies in the after event later.
            $app['auth.loaded'] = true;

            return new AuthManager($app);
        });

        $this->app->singleton('auth.driver', function ($app) {
            return $app['auth']->guard();
        });
    }

    /**
     * Register a resolver for the authenticated user.
     *
     * @return void
     */
    protected function registerUserResolver()
    {
        $this->app->bind(
            AuthenticatableContract::class, function ($app) {
                return call_user_func($app['auth']->userResolver());
            }
        );

    }

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->extend('command.auth.make', function () {
            return new AuthMakeCommand;
        });

        $this->loadMigrationsFrom(__DIR__ . '/migrations');

        $this->publishes([
            __DIR__ . '/../config/auth-verification.php' => config_path('auth-verification.php'),
        ]);

        if ($this->app->runningInConsole()) {
            $this->commands([
                ClearVerificationsCommand::class,
            ]);
        }

    }

}
