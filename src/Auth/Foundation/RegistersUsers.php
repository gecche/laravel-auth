<?php

namespace Gecche\Foundation\Auth;

use Gecche\Auth\Foundation\SendsVerificationEmails;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers as RegistersUsersLaravel;

trait RegistersUsers
{
    use RegistersUsersLaravel;
    use SendsVerificationEmails;



    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        event(new Registered($user = $this->create($request->all())));

        $this->guard()->login($user);

        return $this->registered($request, $user)
                    ?: ($this->verificationStep($request, $user)
                        ?: redirect($this->redirectPath())->with('status',trans('register.registered')));
    }


    protected function verificationStep($request, $user) {

        if (!$user->getAuthVerified()) {

            return $this->sendVerificationEmail($request);


        }


    }



}
