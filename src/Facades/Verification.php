<?php

namespace Gecche\Auth\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Illuminate\Auth\Passwords\PasswordBroker
 */
class Verification extends Facade
{
    /**
     * Constant representing a successfully sent reminder.
     *
     * @var string
     */
    const VERIFICATION_LINK_SENT = 'verification.sent';

    /**
     * Constant representing a successfully email verification.
     *
     * @var string
     */
    const VERIFICATION_VERIFIED = 'verification.verified';

    /**
     * Constant representing a successfully email verification on a deactivated user.
     *
     * @var string
     */
    const VERIFICATION_VERIFIED_DEACTIVATED = 'verification.verified-deactivated';

    /**
     * Constant representing a successfully sent reminder.
     *
     * @var string
     */
    const VERIFICATION_ALREADY= 'verification.already';

    /**
     * Constant representing the user not found response.
     *
     * @var string
     */
    const INVALID_USER = 'verification.user';


    /**
     * Constant representing an invalid token.
     *
     * @var string
     */
    const INVALID_TOKEN = 'verification.token';

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() {
        return 'auth.verification';
    }

}
