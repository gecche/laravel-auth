<?php namespace Cupparis\Auth;

use Cupparis\Auth\Contracts\UserProvider;
use Illuminate\Contracts\Auth\Authenticatable as UserContract;
use Cupparis\Auth\Contracts\Verifiable;
use Cupparis\Auth\Contracts\Activable;

class DatabaseUserProvider extends \Illuminate\Auth\DatabaseUserProvider implements UserProvider {



	/**
	 * Validate a user against the given credentials.
	 *
	 * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
	 * @param  array  $credentials
	 * @return bool
	 */
    public function validateCredentials(UserContract $user, array $credentials)
    {
        if (!parent::validateCredentials($user, $credentials)) {
            throw new Exception('login.invalid');
        }

        return true;

    }

    public function updateAuthVerified(Verifiable $user, $value)
    {
        $this->conn->table($this->table)
            ->where('id', $user->getAuthIdentifier())
            ->update([$user->getAuthVerifiedName() => $value]);
    }

    public function isVerified(Verifiable $user)
    {
        if (!$user->getAuthVerified())
        {
            throw new Exception('login.unverified');
        }

    }


    /**
     * Update the "activated" boolean for the given user in storage.
     *
     * @param \Cupparis\Auth\UserInterface $user
     * @param  bool $value
     * @return void
     */
    public function updateAuthActivated(Activable $user, $value)
    {

        $this->conn->table($this->table)
            ->where('id', $user->getAuthIdentifier())
            ->update([$user->getAuthActivatedName() => $value]);
    }

    public function isActivated(Activable $user)
    {

        if (!$user->getAuthActivated())
        {
            throw new Exception('login.deactivated');
        }

    }

}
