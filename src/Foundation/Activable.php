<?php namespace Gecche\Auth\Foundation;

trait Activable {

    /**
     * Check if a user has been deactivated.
     *
     * @return string
     */
    public function getAuthActivated()
    {
        return $this->{$this->getAuthActivatedName()};
    }

    /**
     * Set value of auth deactived.
     *
     * @param  string  $value
     * @return string
     */
    public function setAuthActivated($value)
    {
        return $this->{$this->getAuthActivatedName()} = $value;
    }

    /**
     * Get the column name for the "auth deactivated" flag.
     *
     * @return string
     */
    public function getAuthActivatedName()
    {
        return 'activated';
    }
}
