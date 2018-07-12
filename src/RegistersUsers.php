<?php

namespace Cupparis\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\NewsletterEmail;
use Exception;
use Cupparis\Auth\Contracts\VerificationBroker;

trait RegistersUsers
{
    use \Illuminate\Foundation\Auth\RegistersUsers;


    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postRegister(Request $request,VerificationBroker $verifications)
    {
        $validator = $this->validator($request->all());

        if ($validator->fails()) {
            $this->throwValidationException(
                $request, $validator
            );
        }

        Auth::login($this->create($request->all()));

        $user = Auth::user();

        try {
            $verified = $user->getAuthVerified();

            $newsletter = $request->get('newsletter_registered');
            //Crea newsletter se necessario
            if ($newsletter) {
                $newsletter_email = NewsletterEmail::where('email',$user->email)->first();
                if (is_null($newsletter_email)) {
                    NewsletterEmail::create([
                        'email' => $user->email,
                        'verified' => $user->verified,
                        'activated' => 1,//$user->activated,
                        'user_id' => $user->getKey(),
                    ]);
                } else {
                    $newsletter_email->verified = $user->verified;
                    $newsletter_email->user_id = $user->getKey();
                    $newsletter_email->save();
                }
            }

            if (!$verified) {
                $response = $verifications->sendVerificationLink($request->only('email'), function ($m) {
                    $m->subject($this->getEmailSubject());
                });

                switch ($response) {
                    case VerificationBroker::VERIFICATION_LINK_SENT:
                        return redirect($this->redirectPath())->with('status',trans_uc($response,['email'=>$request->get('email',"")]));

                    default:
                        return redirect()->back()->withErrors(['email' => trans($response)]);
                }
            }
            return redirect($this->redirectPath())->with('status',trans_uc('register.success'));
        } catch (Exception $e) {
            $user->delete();
            return redirect()->back()->withErrors($e->getMessage())->withInput();
        }


        if ($user->getAuthActivated()) {

            $this->auth->login($user);

            return redirect($this->redirectPath());
        }

        return redirect($this->redirectPath())->with('status', trans('auth.to-be-activated'));    }
}
