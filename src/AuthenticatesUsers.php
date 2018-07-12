<?php

namespace Cupparis\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use App\Models\User;

trait AuthenticatesUsers
{
    use \Illuminate\Foundation\Auth\AuthenticatesUsers;


    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postLogin(Request $request)
    {

        $this->validate($request, [
            $this->loginUsername() => 'required', 'password' => 'required',
        ]);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        $throttles = $this->isUsingThrottlesLoginsTrait();

        if (false && $throttles && $this->hasTooManyLoginAttempts($request)) {
            return $this->sendLockoutResponse($request);
        }

        $credentials = $this->getCredentials($request);

        if (filter_var($credentials['email'], FILTER_VALIDATE_EMAIL) === false) {
            $userFromUsername = User::where('username',$credentials['email'])->first();
            if ($userFromUsername && $userFromUsername->getKey()) {
                $credentials['email'] = $userFromUsername->email;
            }
        }

        Log::info('postlogin3'.print_r($credentials,true));

        try {
            if (Auth::attempt($credentials, $request->has('remember'))) {
                return $this->handleUserWasAuthenticated($request, $throttles);
            }
        }
        catch (\Exception $e) {
            return redirect($this->loginPath())
                ->withInput($request->only($this->loginUsername(), 'remember'))
                ->withErrors([
                    trans($e->getMessage())
                ]);
        }


        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        if ($throttles) {
            $this->incrementLoginAttempts($request);
        }

        return redirect($this->loginPath())
            ->withInput($request->only($this->loginUsername(), 'remember'))
            ->withErrors([
                $this->loginUsername() => $this->getFailedLoginMessage(),
            ]);
    }

}
