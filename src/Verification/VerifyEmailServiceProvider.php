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
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return ['auth.verification', 'auth.verification.tokens'];
	}

}
