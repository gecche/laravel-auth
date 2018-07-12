<?php namespace Cupparis\Auth\Contracts;

interface UserProvider extends \Illuminate\Contracts\Auth\UserProvider {

    /**
     * Update the "verified" boolean for the given user in storage.
     *
     * @param \Cupparis\Auth\UserInterface  $user
     * @param  bool   $value
     * @return void
     */
    public function updateAuthVerified(Verifiable $user, $value);

    public function isVerified(Verifiable $user);

    public function updateAuthActivated(Activable $user, $value);

    public function isActivated(Activable $user);
}
