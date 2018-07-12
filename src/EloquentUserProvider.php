<?php namespace Cupparis\Auth;

use Cupparis\Auth\Contracts\UserProvider;
use Cupparis\Auth\Contracts\Verifiable as VerifiableContract;
use Cupparis\Auth\Contracts\Activable as ActivableContract;
use Illuminate\Contracts\Auth\Authenticatable as UserContract;
use Exception;

class EloquentUserProvider extends \Illuminate\Auth\EloquentUserProvider implements UserProvider {

    /**
     * Update the "verified" boolean for the given user in storage.
     *
     * @param \Cupparis\Auth\UserInterface $user
     * @param  bool $value
     * @return void
     */
    public function updateAuthVerified(VerifiableContract $user, $value)
    {
        $user->setAuthVerified($value);

        $user->forceSave();
    }

    public function validateCredentials(UserContract $user, array $credentials)
    {
        if (!parent::validateCredentials($user, $credentials)) {
            throw new Exception('login.invalid');
        }

        return true;

    }

    public function isVerified(VerifiableContract $user)
    {
        if (!$user->getAuthVerified())
        {
            throw new Exception('login.unverified');
        }
        return true;

    }

    /**
     * Update the "activated" boolean for the given user in storage.
     *
     * @param \Cupparis\Auth\UserInterface $user
     * @param  bool $value
     * @return void
     */
    public function updateAuthActivated(ActivableContract $user, $value)
    {
        $user->setAuthActivated($value);

        $user->forceSave();
    }

    public function isActivated(ActivableContract $user)
    {

        if (!$user->getAuthActivated())
        {
            throw new Exception('login.deactivated');
        }
        return true;

    }


}
