<?php namespace Gecche\Auth\Contracts;

use Illuminate\Contracts\Auth\CanResetPassword;

interface Verifiable extends CanResetPassword {

    /**
     * Check if a user is verified.
     *
     * @return string
     */
    public function getAuthVerified();

    /**
     * Set value of auth verified.
     *
     * @param  string  $value
     * @return string
     */
    public function setAuthVerified($value);

    /**
     * Get the column name for the "auth verified" flag.
     *
     * @return string
     */
    public function getAuthVerifiedName();

    /**
     * Get the e-mail address where password reset links are sent.
     *
     * @return string
     */
    public function getEmailForVerification();

    /**
     * Send the verification notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendVerificationNotification($token);

}
