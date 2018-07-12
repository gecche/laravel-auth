<?php namespace Cupparis\Auth\Console;

use Illuminate\Console\Command;

class ClearVerificationsCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'auth:clear-verifications';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Flush expired email verifications tokens';

	/**
	 * Execute the console command.
	 *
	 * @return void
	 */
	public function fire()
	{
		$this->laravel['auth.verification.tokens']->deleteExpired();

		$this->info('Expired verification tokens cleared!');
	}

}
