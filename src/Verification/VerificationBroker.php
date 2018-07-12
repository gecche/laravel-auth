<?php namespace Cupparis\Auth\Verification;

use Closure;
use \Illuminate\Auth\Passwords\TokenRepositoryInterface;
use Cupparis\Auth\Contracts\UserProvider;
use Illuminate\Contracts\Mail\Mailer as MailerContract;
use Cupparis\Auth\Contracts\VerificationBroker as VerificationBrokerContract;
use Cupparis\Auth\Contracts\ToBeVerified as ToBeVerifiedContract;

class VerificationBroker implements VerificationBrokerContract {

    /**
     * The password token repository.
     *
     * @var \Illuminate\Auth\Passwords\TokenRepositoryInterface  $tokens
     */
    protected $tokens;

	/**
	 * The user provider implementation.
	 *
	 * @var \Cupparis\Auth\Contracts\UserProvider
	 */
	protected $users;

	/**
	 * The mailer instance.
	 *
	 * @var \Illuminate\Contracts\Mail\Mailer
	 */
	protected $mailer;

	/**
	 * The view of the verification reminder e-mail.
	 *
	 * @var string
	 */
	protected $emailView;

	/**
	 * Create a new verification broker instance.
	 *
     * @param  \Illuminate\Auth\Passwords\TokenRepositoryInterface  $tokens
     * @param  \Illuminate\Contracts\Auth\UserProvider  $users
     * @param  \Illuminate\Contracts\Mail\Mailer  $mailer
     * @param  string  $emailView
     * @return void
	 */
	public function __construct(TokenRepositoryInterface $tokens,
                                UserProvider $users,
                                MailerContract $mailer,
                                $emailView)
	{
		$this->users = $users;
		$this->mailer = $mailer;
		$this->tokens = $tokens;
		$this->emailView = $emailView;
	}

    /**
     * @param string $emailView
     */
    public function setEmailView($emailView)
    {
        $this->emailView = $emailView;
    }



	/**
	 * Send a verification link to a user.
	 *
	 * @param  array    $credentials
	 * @param  Closure  $callback
	 * @return string
	 */
	public function sendVerificationLink(array $credentials, Closure $callback = null)
	{
		// First we will check to see if we found a user at the given credentials and
		// if we did not we will redirect back to this current URI with a piece of
		// "flash" data in the session to indicate to the developers the errors.
		$user = $this->getUser($credentials);

		if (is_null($user) || !$user->getAuthActivated())
		{
            return VerificationBrokerContract::INVALID_USER;
		}

        if ($user->getAuthVerified()) {
            return VerificationBrokerContract::VERIFICATION_ALREADY;
        }
        // Once we have the reset token, we are ready to send the message out to this
        // user with a link to verify their email. We will then redirect back to
        // the current URI having nothing set in the session to indicate errors.
		$token = $this->tokens->create($user);

		$this->emailVerificationLink($user, $token, $callback);

		return VerificationBrokerContract::VERIFICATION_LINK_SENT;
	}

	/**
	 * Send the verification reminder e-mail.
	 *
	 * @param  \Cupparis\Auth\Reminders\RemindableInterface  $user
	 * @param  string   $token
	 * @param  Closure  $callback
	 * @return int
	 */
	public function emailVerificationLink(ToBeVerifiedContract $user, $token, Closure $callback = null)
	{
		// We will use the reminder view that was given to the broker to display the
		// verification reminder e-mail. We'll pass a "token" variable into the views
		// so that it may be displayed for an user to click for email verification.
		$view = $this->emailView;

		return $this->mailer->send($view, compact('token', 'user'), function($m) use ($user, $token, $callback)
		{
			$m->to($user->getEmailForVerification());

			if ( ! is_null($callback)) call_user_func($callback, $m, $user, $token);
		});
	}

	/**
	 * Verify the email for the given token.
	 *
	 * @param  array    $credentials
	 * @param  Closure  $callback
	 * @return mixed
	 */
	public function verify(array $credentials)
	{
		// If the responses from the validate method is not a user instance, we will
		// assume that it is a redirect and simply return it from this method and
		// the user is properly redirected having an error message on the post.
		$user = $this->validateVerification($credentials);

		if ( ! $user instanceof ToBeVerifiedContract)
		{
			return $user;
		}

		$this->users->updateAuthVerified($user, 1);

		$this->tokens->delete($credentials['token']);

		return VerificationBrokerContract::VERIFICATION_VERIFIED;
	}

	/**
	 * Validate a email verification for the given credentials.
	 *
	 * @param  array  $credentials
	 * @return \Cupparis\Auth\Reminders\RemindableInterface
	 */
	public function validateVerification(array $credentials)
	{
		if (is_null($user = $this->getUser($credentials)))
		{
            return VerificationBrokerContract::INVALID_USER;
		}

		if ( ! $this->tokens->exists($user, $credentials['token']))
		{
            return VerificationBrokerContract::INVALID_TOKEN;
		}

		return $user;
	}

	/**
	 * Get the user for the given credentials.
	 *
	 * @param  array  $credentials
	 * @return \Illuminate\Contracts\Auth\ToBeVerified
	 *
	 * @throws \UnexpectedValueException
	 */
	public function getUser(array $credentials)
	{
		$credentials = array_except($credentials, array('token'));

		$user = $this->users->retrieveByCredentials($credentials);

		if ($user && ! $user instanceof ToBeVerifiedContract)
		{
			throw new \UnexpectedValueException("User must implement ToBeVerified contract.");
		}

		return $user;
	}

	/**
	 * Get the verification token repository implementation.
	 *
     * @return \Illuminate\Auth\Passwords\TokenRepositoryInterface
	 */
	protected function getRepository()
	{
		return $this->tokens;
	}

}
