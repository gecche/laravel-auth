<?php

namespace Gecche\Auth;

use Illuminate\Contracts\Session\Session;
use Gecche\Auth\Contracts\UserProvider;
use Symfony\Component\HttpFoundation\Request;

class SessionGuard extends \Illuminate\Auth\SessionGuard
{
    /**
     * Create a new authentication guard.
     *
     * @param  string  $name
     * @param  \Gecche\Auth\UserProvider  $provider
     * @param  \Illuminate\Contracts\Session\Session  $session
     * @param  \Symfony\Component\HttpFoundation\Request  $request
     * @return void
     */
    public function __construct($name,
                                UserProvider $provider,
                                Session $session,
                                Request $request = null)
    {
        $this->name = $name;
        $this->session = $session;
        $this->request = $request;
        $this->provider = $provider;
    }

    /**
     * Attempt to authenticate a user using the given credentials.
     *
     * @param  array  $credentials
     * @param  bool   $remember
     * @return bool
     */
    public function attempt(array $credentials = [], $remember = false)
    {
        $this->fireAttemptEvent($credentials, $remember);

        $this->lastAttempted = $user = $this->provider->retrieveByCredentials($credentials);

        // If an implementation of UserInterface was returned, we'll ask the provider
        // to validate the user against the given credentials, and if they are in
        // fact valid we'll log the users into the application and return true.
        if ($this->hasValidCredentials($user, $credentials)
            && $this->isVerified($user)
            && $this->isActivated($user)
        ) {
            $this->login($user, $remember);

            return true;
        }

        // If the authentication attempt fails we will fire an event so that the user
        // may be notified of any suspicious attempts to access their account from
        // an unrecognized user. A developer may listen to this event as needed.
        $this->fireFailedEvent($user, $credentials);

        return false;
    }


    /**
     * Determine if the user is verified.
     *
     * @param  mixed  $user
     * @return bool
     */
    protected function isVerified($user) {
        return !config('auth-verification.check_verification') || $this->provider->isVerified($user);
    }

    /**
     * Determine if the user is activated.
     *
     * @param  mixed  $user
     * @return bool
     */
    protected function isActivated($user) {
        return !config('auth-verification.check_activation') || $this->provider->isActivated($user);
    }


}
