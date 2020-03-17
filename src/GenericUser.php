<?php

namespace Gecche\Auth;

use Gecche\Auth\Contracts\Authenticatable as UserContract;

class GenericUser extends \Illuminate\Auth\GenericUser implements UserContract
{
    /**
     * Check if a user has been activated.
     *
     * @return string
     */
    public function getAuthActivated()
    {
        return $this->attributes[$this->getAuthActivatedName()];
    }

    /**
     * Set value of auth activated.
     *
     * @param  string $value
     * @return string
     */
    public function setAuthActivated($value)
    {
        $this->attributes[$this->getAuthActivatedName()] = $value;
    }

    /**
     * Get the column name for the "auth activated" flag.
     *
     * @return string
     */
    public function getAuthActivatedName()
    {
        return 'activated';
    }

    /**
     * Check if a user is verified.
     *
     * @return string
     */
    public function getAuthVerified()
    {
        return $this->attributes[$this->getAuthVerifiedName()];
    }

    /**
     * Set value of auth verified.
     *
     * @param  string $value
     * @return string
     */
    public function setAuthVerified($value)
    {
        $this->attributes[$this->getAuthVerifiedName()] = $value;
    }

    /**
     * Get the column name for the "auth verified" flag.
     *
     * @return string
     */
    public function getAuthVerifiedName()
    {
        return 'verified';
    }
}
