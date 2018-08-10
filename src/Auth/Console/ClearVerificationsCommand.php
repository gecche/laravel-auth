<?php namespace Gecche\Auth\Console;

use Illuminate\Console\Command;

class ClearVerificationsCommand extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auth:clear-verifications {name? : The name of the verification broker}';

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
	public function handle()
	{
		$this->laravel['auth.verification']->broker($this->argument('name'))->getRepository()->deleteExpired();

		$this->info('Expired verification tokens cleared!');
	}

}
