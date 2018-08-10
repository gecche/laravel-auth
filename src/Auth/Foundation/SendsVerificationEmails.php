<?php

namespace Gecche\Auth\Foundation;

use Gecche\Auth\Facades\Verification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

trait SendsVerificationEmails
{
    /**
     * Display the form to request a password reset link.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLinkRequestForm()
    {
        return view('auth.verification.email');
    }

    /**
     * Send a reset link to the given user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function sendVerificationEmail(Request $request)
    {
        $this->validateEmail($request);

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $response = $this->broker()->sendVerificationLink(
            $request->only('email')
        );

        return $response == Verification::VERIFICATION_LINK_SENT
            ? $this->sendVerificationResponse($request, $response)
            : $this->sendVerificationFailedResponse($request, $response);
    }

    /**
     * Validate the email for the given request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    protected function validateEmail(Request $request)
    {
        $this->validate($request, ['email' => 'required|email']);
    }

    /**
     * Get the response for a successful verification link.
     *
     * @param  string  $response
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    protected function sendVerificationResponse($request, $response)
    {
        return redirect($this->redirectVerificationPath())->with('status',trans($response,['email'=>$request->get('email',"")]));
    }

    /**
     * Get the response for a failed verification link.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $response
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    protected function sendVerificationFailedResponse(Request $request, $response)
    {
        return back()->withErrors(
            ['email' => trans($response)]
        );
    }

    /**
     * Get the broker to be used during password reset.
     *
     * @return \Illuminate\Contracts\Auth\PasswordBroker
     */
    public function broker()
    {
        return Verification::broker();
    }

    /**
     * Get the post register / login redirect path.
     *
     * @return string
     */
    public function redirectVerificationPath()
    {
        if (method_exists($this, 'redirectVerificationTo')) {
            return $this->redirectVerificationTo();
        }

        return property_exists($this, 'redirectVerificationTo') ? $this->redirectVerificationTo : '/login';
    }
}
