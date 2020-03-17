<?php namespace Gecche\Auth\Contracts;

interface Activable {

    /**
     * Check if a user has been deactivated.
     *
     * @return string
     */
    public function getAuthActivated();

    /**
     * Set value of auth deactived.
     *
     * @param  string  $value
     * @return string
     */
    public function setAuthActivated($value);

    /**
     * Get the column name for the "auth deactivated" flag.
     *
     * @return string
     */
    public function getAuthActivatedName();

}
