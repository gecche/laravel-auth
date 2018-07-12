<?php namespace Cupparis\Auth;

class AuthServiceProvider extends \Illuminate\Auth\AuthServiceProvider {


    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        /*
        $this->app->bind(
            'Cupparis\Auth\Contracts\UserProvider',
            ['Cupparis\Auth\EloquentUserProvider']
        );
        */
        parent::register();

        $this->app->alias(
            'auth.verification',
            \Cupparis\Auth\Contracts\VerificationBroker::class
        );
        $this->app->alias(
            'auth.verification',
            \Cupparis\Auth\Verification\VerificationBroker::class
        );
        $this->app->alias(
            'auth.verification.tokens',
            \Illuminate\Auth\Passwords\TokenRepositoryInterface::class
        );

    }

    /**
     * Register the authenticator services.
     *
     * @return void
     */
    protected function registerAuthenticator()
    {
        $this->app->singleton('auth', function($app)
        {
            // Once the authentication service has actually been requested by the developer
            // we will set a variable in the application indicating such. This helps us
            // know that we need to set any queued cookies in the after event later.
            $app['auth.loaded'] = true;

            return new AuthManager($app);
        });

        $this->app->singleton('auth.driver', function($app)
        {
            return $app['auth']->driver();
        });
    }

    /**
	 * Register a resolver for the authenticated user.
	 *
	 * @return void
	 */
	protected function registerUserResolver()
	{
		$this->app->bind(\Illuminate\Contracts\Auth\Authenticatable::class, function($app)
		{
			return $app['auth']->user();
		});

        $this->app->bind(\Cupparis\Auth\Contracts\Verifiable::class, function($app)
        {
            return $app['auth']->user();
        });

        $this->app->bind(\Cupparis\Auth\Contracts\Activable::class, function($app)
        {
            return $app['auth']->user();
        });
	}

}
