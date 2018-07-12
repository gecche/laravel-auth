<?php namespace Cupparis\Auth;

use App\Models\NewsletterEmail;
use App\Models\User;
use Cupparis\Auth\Contracts\VerificationBroker;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

trait VerifyUsers {

	/**
	 * The verification broker implementation.
	 *
	 * @var VerificationBroker
	 */
	protected $verifications;

	/**
	 * Display the form to request an email verificaiton link.
	 *
	 * @return Response
	 */
	public function getEmail()
	{
		return view('auth.verify');
	}

	/**
	 * Send an email verification link to the given user.
	 *
	 * @param  Request  $request
	 * @return Response
	 */
	public function postEmail(Request $request)
	{
		$this->validate($request, ['email' => 'required|email']);

		$response = $this->verifications->sendVerificationLink($request->only('email'), function($m)
		{
			$m->subject($this->getEmailSubject());
		});

		switch ($response)
		{
			case VerificationBroker::VERIFICATION_LINK_SENT:
				return redirect()->back()->with('status', trans($response));

            default:
				return redirect()->back()->withErrors(['email' => trans($response)]);
		}
	}

	/**
	 * Get the e-mail subject line to be used for the reset link email.
	 *
	 * @return string
	 */
	protected function getEmailSubject()
	{
		return isset($this->subject) ? $this->subject : 'verification.email-subject';
	}

	/**
	 * Display the password reset view for the given token.
	 *
	 * @param  string  $token
	 * @return Response
	 */
	public function getVerify(Request $request)
	{

        $this->validate($request, [
            'token' => 'required',
            'email' => 'required|email',
        ]);

        $credentials = $request->only(
            'email', 'token'
        );

        $response = $this->verifications->verify($credentials);

        switch ($response)
        {
            case VerificationBroker::VERIFICATION_VERIFIED:

                $user = $this->verifications->getUser($credentials);
                if (!is_null($user->newsletter)) {
                    $user->newsletter->verified = true;
                    $user->newsletter->save();
                }
                return redirect($this->redirectPath())->with('success',trans($response));

            default:
                return view('auth.verify')
                    ->withErrors(['token' =>  trans($response)]);
        }
	}

	/**
	 * Get the post register / login redirect path.
	 *
	 * @return string
	 */
	public function redirectPath()
	{
		if (property_exists($this, 'redirectPath'))
		{
			return $this->redirectPath;
		}

		return property_exists($this, 'redirectTo') ? $this->redirectTo : '/home';
	}

    /**
     * Display the form to request an email verificaiton link.
     *
     * @return Response
     */
    public function getThankyou()
    {
        return view('auth.thankyou-verified');
    }

}
