<?php namespace Cupparis\Auth\Passwords;

use Closure;
use UnexpectedValueException;
use Cupparis\Auth\Contracts\UserProvider;
use Illuminate\Contracts\Mail\Mailer as MailerContract;
use Illuminate\Contracts\Auth\PasswordBroker as PasswordBrokerContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class PasswordBroker extends \Illuminate\Auth\Passwords\PasswordBroker {

	/**
	 * Send a password reset link to a user.
	 *
	 * @param  array  $credentials
	 * @param  \Closure|null  $callback
	 * @return string
	 */
	public function sendResetLink(array $credentials, Closure $callback = null)
	{
		// First we will check to see if we found a user at the given credentials and
		// if we did not we will redirect back to this current URI with a piece of
		// "flash" data in the session to indicate to the developers the errors.
		$user = $this->getUser($credentials);

		if (is_null($user) || !$user->getAuthActivated())
		{
			return PasswordBrokerContract::INVALID_USER;
		}

		// Once we have the reset token, we are ready to send the message out to this
		// user with a link to reset their password. We will then redirect back to
		// the current URI having nothing set in the session to indicate errors.
		$token = $this->tokens->create($user);

		$this->emailResetLink($user, $token, $callback);

		return PasswordBrokerContract::RESET_LINK_SENT;
	}


}
