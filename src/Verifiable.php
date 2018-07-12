<?php namespace Cupparis\Auth;

trait Verifiable {

    /**
     * Check if a user is verified.
     *
     * @return string
     */
    public function getAuthVerified()
    {
        return $this->{$this->getAuthVerifiedName()};
    }

    /**
     * Set value of auth verified.
     *
     * @param  string  $value
     * @return string
     */
    public function setAuthVerified($value)
    {
        return $this->{$this->getAuthVerifiedName()} = $value;
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
