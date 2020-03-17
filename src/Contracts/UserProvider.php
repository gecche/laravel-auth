<?php namespace Gecche\Auth\Contracts;

interface UserProvider extends \Illuminate\Contracts\Auth\UserProvider {

    /**
     * Update the "verified" boolean for the given user in storage.
     *
     * @param \Cupparis\Auth\UserInterface  $user
     * @param  bool   $value
     * @return void
     */
    public function updateAuthVerified(Authenticatable $user, $value);

    public function isVerified(Authenticatable $user);

    public function updateAuthActivated(Authenticatable $user, $value);

    public function isActivated(Authenticatable $user);
}
