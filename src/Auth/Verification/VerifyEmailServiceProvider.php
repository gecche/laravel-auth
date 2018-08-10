<?php namespace Gecche\Auth\Verification;

use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Passwords\DatabaseTokenRepository as DbRepository;

class VerifyEmailServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = true;

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
        $this->registerVerificationBroker();
	}

	/**
	 * Register the verification broker instance.
	 *
	 * @return void
	 */
	protected function registerVerificationBroker()
	{
		$this->app->singleton('auth.verification', function($app)
		{
			return new VerificationBrokerManager($app);
		});

        $this->app->singleton('auth.verification.broker', function($app)
        {
            return $app->make('auth.verification')->broker();
        });

	}

    /**
     * Register the token repository implementation.
     *
     * @return void
     */
    protected function registerTokenRepository()
    {
        $this->app->singleton('auth.verification.tokens', function($app)
        {
            $connection = $app['db']->connection();

            // The database token repository is an implementation of the token repository
            // interface, and is responsible for the actual storing of auth tokens and
            // their e-mail addresses. We will inject this table and hash key to it.
            $table = $app['config']['auth.verification.table'];

            $key = $app['config']['app.key'];

            $expire = $app['config']->get('auth.verification.expire', 60);

            return new DbRepository($connection, $table, $key, $expire);
        });
    }

    /**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return ['auth.verification', 'auth.verification.tokens'];
	}

}
