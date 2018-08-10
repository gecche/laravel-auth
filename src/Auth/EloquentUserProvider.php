<?php

namespace Gecche\Auth;

use Gecche\Auth\Contracts\UserProvider;
use Gecche\Auth\Contracts\Authenticatable;

class EloquentUserProvider extends \Illuminate\Auth\EloquentUserProvider implements UserProvider
{


    /**
     * Update the "verified" boolean for the given user in storage.
     *
     * @param \Cupparis\Auth\UserInterface $user
     * @param  bool $value
     * @return void
     */
    public function updateAuthVerified(Authenticatable $user, $value)
    {
        $user->setAuthVerified($value);

        $timestamps = $user->timestamps;

        $user->timestamps = false;

        $user->save();

        $user->timestamps = $timestamps;
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
        $user->setAuthActivated($value);

        $timestamps = $user->timestamps;

        $user->timestamps = false;

        $user->save();

        $user->timestamps = $timestamps;
    }

    public function isActivated(Authenticatable $user)
    {

        return $user->getAuthActivated();

    }

}
