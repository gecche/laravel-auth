<?php namespace Gecche\Auth\Verification;

use Closure;
use \Illuminate\Auth\Passwords\TokenRepositoryInterface;
use Gecche\Auth\Contracts\UserProvider;
use Illuminate\Contracts\Mail\Mailer as MailerContract;
use Gecche\Auth\Contracts\VerificationBroker as VerificationBrokerContract;
use Gecche\Auth\Contracts\Authenticatable as AuthenticatableContract;

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
	 * @var \Gecche\Auth\Contracts\UserProvider
	 */
	protected $users;


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
                                UserProvider $users)
	{
		$this->users = $users;
		$this->tokens = $tokens;
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

		if (is_null($user))
		{
            return static::INVALID_USER;
		}

        if ($user->getAuthVerified()) {
            return static::VERIFICATION_ALREADY;
        }
        // Once we have the reset token, we are ready to send the message out to this
        // user with a link to verify their email. We will then redirect back to
        // the current URI having nothing set in the session to indicate errors.
        $user->sendVerificationNotification(
            $this->tokens->create($user)
        );

		return static::VERIFICATION_LINK_SENT;
	}

	/**
	 * Verify the email for the given token.
	 *
	 * @param  array    $credentials
	 * @param  Closure  $callback
	 * @return mixed
	 */
	public function verify(array $credentials, Closure $callback)
	{
		// If the responses from the validate method is not a user instance, we will
		// assume that it is a redirect and simply return it from this method and
		// the user is properly redirected having an error message on the post.
		$user = $this->validateVerification($credentials);

		if ( ! $user instanceof AuthenticatableContract)
		{
			return $user;
		}

		$callback($user,1);

		$this->deleteToken($user);

		return $user->getAuthActivated()
            ? static::VERIFICATION_VERIFIED
            : static::VERIFICATION_VERIFIED_DEACTIVATED;
	}

    /**
     * Create a new password reset token for the given user.
     *
     * @param  \Gecche\Auth\Contracts\Verifiable $user
     * @return string
     */
    public function createToken(AuthenticatableContract $user)
    {
        return $this->createToken($user);
    }

    /**
     * Delete password reset tokens of the given user.
     *
     * @param  \Gecche\Auth\Contracts\Verifiable $user
     * @return void
     */
    public function deleteToken(AuthenticatableContract $user)
    {
        $this->tokens->delete($user);
    }

    /**
	 * Validate a email verification for the given credentials.
	 *
	 * @param  array  $credentials
	 * @return \Gecche\Auth\Contracts\Verifiable|string
	 */
	public function validateVerification(array $credentials)
	{
		if (is_null($user = $this->getUser($credentials)))
		{
            return static::INVALID_USER;
		}

		if ( ! $this->tokens->exists($user, $credentials['token']))
		{
            return static::INVALID_TOKEN;
		}

		return $user;
	}

	/**
	 * Get the user for the given credentials.
	 *
	 * @param  array  $credentials
	 * @return \Gecche\Auth\Contracts\Verifiable
	 *
	 * @throws \UnexpectedValueException
	 */
	public function getUser(array $credentials)
	{
		$credentials = array_except($credentials, ['token']);

		$user = $this->users->retrieveByCredentials($credentials);

		if ($user && ! $user instanceof AuthenticatableContract)
		{
			throw new \UnexpectedValueException("User must implement Verifiable contract.");
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
