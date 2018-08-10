<?php namespace Gecche\Auth\Contracts;

use Closure;

interface VerificationBroker {

	/**
	 * Constant representing a successfully sent reminder.
	 *
	 * @var int
	 */
	const VERIFICATION_LINK_SENT = 'verification.sent';

	/**
	 * Constant representing a successfully email verification.
	 *
	 * @var int
	 */
	const VERIFICATION_VERIFIED = 'verification.verified';

    /**
     * Constant representing a successfully email verification on a deactivated user.
     *
     * @var int
     */
    const VERIFICATION_VERIFIED_DEACTIVATED = 'verification.verified-deactivated';

    /**
     * Constant representing a successfully sent reminder.
     *
     * @var int
     */
    const VERIFICATION_ALREADY= 'verification.already';

	/**
	 * Constant representing the user not found response.
	 *
	 * @var int
	 */
	const INVALID_USER = 'verification.user';


	/**
	 * Constant representing an invalid token.
	 *
	 * @var int
	 */
	const INVALID_TOKEN = 'verification.token';

	/**
	 * Send a password reset link to a user.
	 *
	 * @param  array  $credentials
	 * @param  \Closure|null  $callback
	 * @return string
	 */
	public function sendVerificationLink(array $credentials, Closure $callback = null);

	/**
	 * Verify the email for the given token.
	 *
	 * @param  array     $credentials
	 * @return mixed
	 */
	public function verify(array $credentials, Closure $callback);


	/**
	 * Determine if the passwords match for the request.
	 *
	 * @param  array  $credentials
	 * @return bool
	 */
	public function validateVerification(array $credentials);


}
