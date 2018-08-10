<?php namespace Gecche\Auth\Foundation;

use Gecche\Auth\Notifications\Verification as  VerificationNotification;

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

    /**
     * Get the e-mail address where password reset links are sent.
     *
     * @return string
     */
    public function getEmailForVerification()
    {
        return $this->email;
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendVerificationNotification($token)
    {
        $this->notify(new VerificationNotification($token));
    }

}
