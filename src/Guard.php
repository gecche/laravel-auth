<?php namespace Cupparis\Auth;

use Cupparis\Auth\Contracts\Activable as ActivableContract;
use Cupparis\Auth\Contracts\Verifiable as VerifiableContract;
use Exception;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class Guard extends \Illuminate\Auth\Guard {

	/**
	 * Attempt to authenticate a user using the given credentials.
	 *
	 * @param  array  $credentials
	 * @param  bool   $remember
	 * @param  bool   $login
	 * @return bool
	 */
	public function attempt(array $credentials = [], $remember = false, $login = true)
	{
		$this->fireAttemptEvent($credentials, $remember, $login);

		$this->lastAttempted = $user = $this->provider->retrieveByCredentials($credentials);

		// If an implementation of UserInterface was returned, we'll ask the provider
		// to validate the user against the given credentials, and if they are in
		// fact valid we'll log the users into the application and return true.
        try {
            if ($this->hasValidCredentials($user, $credentials)
                && $this->isVerified($user)
                && $this->isActivated($user)
            ) {
                Log::info('guard1');
                if ($login) {
                    $this->login($user, $remember);
                }

                Log::info('guard2');
                return true;
            }
        } catch (Exception $e) {
            throw $e;
        }

		return false;
	}

    protected function isVerified(VerifiableContract $user) {
        return $this->provider->isVerified($user);
    }

    protected function isActivated(ActivableContract $user) {
        return $this->provider->isActivated($user);
    }

    /**
     * Determine if the user matches the credentials.
     *
     * @param  mixed  $user
     * @param  array  $credentials
     * @return bool
     */
    protected function hasValidCredentials($user, $credentials)
    {
        $return = ! is_null($user) && $this->provider->validateCredentials($user, $credentials);
        if (!$return)
            throw new Exception('login.invalid');
        return true;
    }

    public function getRoleId() {
        if ($this->user()) {
            return $this->user()->getRoleId();
        }
        return null;
    }

    public function isAdmin() {
        $role = $this->getRoleId();
        if (!$role) {
            return false;
        }

        $adminRoles = Config::get('acl.admin_roles',['ADMIN']);

        if (in_array($role,$adminRoles))
            return true;

        return false;

    }
}
