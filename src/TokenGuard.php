<?php

namespace Gecche\Auth;

use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\Guard;
use Gecche\Auth\Contracts\UserProvider;

class TokenGuard extends \Illuminate\Auth\TokenGuard implements Guard
{
    use GuardHelpers;


    /**
     * Create a new authentication guard.
     *
     * @param  \Illuminate\Contracts\Auth\UserProvider  $provider
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    public function __construct(UserProvider $provider, Request $request)
    {
        $this->request = $request;
        $this->provider = $provider;
        $this->inputKey = 'api_token';
        $this->storageKey = 'api_token';
    }



    /**
     * Validate a user's credentials.
     *
     * @param  array  $credentials
     * @return bool
     */
    public function validate(array $credentials = [])
    {
        if (empty($credentials[$this->inputKey])) {
            return false;
        }

        $credentials = [$this->storageKey => $credentials[$this->inputKey]];

        $user = $this->provider->retrieveByCredentials($credentials);
        if ($user
            && $this->isVerified($user)
            && $this->isActivated($user)
        ) {
            return true;
        }

        return false;
    }

    /**
     * Determine if the user is verified.
     *
     * @param  mixed  $user
     * @return bool
     */
    protected function isVerified($user) {
        return !config('auth-verification.api_check_verification') || $this->provider->isVerified($user);
    }

    /**
     * Determine if the user is activated.
     *
     * @param  mixed  $user
     * @return bool
     */
    protected function isActivated($user) {
        return !config('auth-verification.api_check_activation') || $this->provider->isActivated($user);
    }

}
