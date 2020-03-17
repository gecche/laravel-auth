<?php

namespace Gecche\Auth;

use Gecche\Auth\Contracts\Authenticatable;
use Illuminate\Support\Str;
use Gecche\Auth\Contracts\UserProvider;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Contracts\Hashing\Hasher as HasherContract;
use Illuminate\Contracts\Auth\Authenticatable as UserContract;
use Gecche\Auth\Contracts\Verifiable as VerifiableContract;
use Gecche\Auth\Contracts\Activable as ActivableContract;

class DatabaseUserProvider extends \Illuminate\Auth\DatabaseUserProvider implements UserProvider
{

    /**
     * Get the generic user.
     *
     * @param  mixed $user
     * @return \Illuminate\Auth\GenericUser|null
     */
    protected function getGenericUser($user)
    {
        if (!is_null($user)) {
            return new GenericUser((array)$user);
        }
    }

    public function updateAuthVerified(Authenticatable $user, $value)
    {
        $this->conn->table($this->table)
            ->where('id', $user->getAuthIdentifier())
            ->update([$user->getAuthVerifiedName() => $value]);
    }

    public function isVerified(Authenticatable $user)
    {
        return $user->getAuthVerified();

    }


    /**
     * Update the "activated" boolean for the given user in storage.
     *
     * @param \Cupparis\Auth\UserInterface $user
     * @param  bool $value
     * @return void
     */
    public function updateAuthActivated(Authenticatable $user, $value)
    {

        $this->conn->table($this->table)
            ->where('id', $user->getAuthIdentifier())
            ->update([$user->getAuthActivatedName() => $value]);
    }

    public function isActivated(Authenticatable $user)
    {

        return $user->getAuthActivated();

    }


}
