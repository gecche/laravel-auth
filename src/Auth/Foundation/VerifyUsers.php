<?php namespace Gecche\Foundation\Auth;

use Gecche\Auth\Facades\Verification;
use Gecche\Auth\Events\Verificated;
use Illuminate\Http\Request;

trait VerifyUsers
{

    /**
     * Display the password reset view for the given token.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function verify(Request $request)
    {

        $this->validate($request, $this->rules(), $this->validationErrorMessages());

        $response = $this->broker()->verify(
            $this->credentials($request), function ($user, $verificationValue) {
            $this->verifyUser($user, $verificationValue);
        }
        );

        // If the password was successfully reset, we will redirect the user back to
        // the application's home authenticated view. If there is an error we can
        // redirect them back to where they came from with their error message.
        return in_array($response, [Verification::VERIFICATION_VERIFIED,Verification::VERIFICATION_VERIFIED_DEACTIVATED])
            ? $this->sendVerificationResponse($response)
            : $this->sendVerificationFailedResponse($request, $response);

    }

    /**
     * Get the post register / login redirect path.
     *
     * @return string
     */
    public function redirectPath()
    {
        if (property_exists($this, 'redirectPath')) {
            return $this->redirectPath;
        }

        return property_exists($this, 'redirectTo') ? $this->redirectTo : '/home';
    }


    /**
     * Get the verification email validation rules.
     *
     * @return array
     */
    protected function rules()
    {
        return [
            'token' => 'required',
            'email' => 'required|email',
        ];
    }

    /**
     * Get the varification email validation error messages.
     *
     * @return array
     */
    protected function validationErrorMessages()
    {
        return [];
    }

    /**
     * Get the email verification credentials from the request.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        return $request->only(
            'email', 'token'
        );
    }


    /**
     * Get the response for a successful email verification.
     *
     * @param  string  $response
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    protected function sendVerificationResponse($response)
    {
        return view('auth.verification.thankyou')->with(
            ['goLogin' => ($response == Verification::VERIFICATION_VERIFIED) ?: false]
        );
    }

    /**
     * Get the response for a failed email verification.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $response
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    protected function sendVerificationFailedResponse(Request $request, $response)
    {
        return redirect()->back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => trans($response)]);
    }


    /**
     * Get the broker to be used during verification.
     *
     * @return \Gecche\Auth\Contracts\VerificationBroker
     */
    public function broker()
    {
        return Verification::broker();
    }

    /**
     * Reset the given user's password.
     *
     * @param  \Gecche\Auth\Contracts\Authenticatable  $user
     * @param  string  $password
     * @return void
     */
    protected function verifyUser($user, $verificationValue)
    {

        $user->setAuthVerified($verificationValue);

        $user->save();

        event(new Verificated($user));

    }

}
