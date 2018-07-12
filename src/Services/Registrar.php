<?php
/**
 * Created by PhpStorm.
 * User: giacomoterreni
 * Date: 04/03/15
 * Time: 18:33
 */

namespace Cupparis\Auth\Services;

use App\Models\User;
use Validator;
use Config;
use Illuminate\Contracts\Auth\Registrar as RegistrarContract;

class Registrar implements RegistrarContract
{

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array $data
     * @return User
     */
    public function create(array $data)
    {
        $toVerify = Config::get('auth.registrar-verification',1);
        $toActivate = Config::get('auth.registrar-activation',1);

        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'verified' => !$toVerify,
            'activated' => $toActivate,
        ]);
    }


}
