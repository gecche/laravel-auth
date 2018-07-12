<?php namespace Cupparis\Auth;

use Cupparis\Auth\Console\ClearVerificationsCommand;
use Illuminate\Support\ServiceProvider;

class GeneratorServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = true;

	/**
	 * The commands to be registered.
	 *
	 * @var array
	 */
	protected $commands = [
		'ClearVerifications',
	];

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		foreach ($this->commands as $command)
		{
			$this->{"register{$command}Command"}();
		}

		$this->commands(
			'command.auth.verifications.clear'
		);
	}

	/**
	 * Register the command.
	 *
	 * @return void
	 */
	protected function registerClearVerificationsCommand()
	{
		$this->app->singleton('command.auth.verifications.clear', function()
		{
			return new ClearVerificationsCommand();
		});
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return [
			'command.auth.verifications.clear'
		];
	}

}
