<?php

return [


    /*
    |--------------------------------------------------------------------------
    | Verification
    |--------------------------------------------------------------------------
    |
    | You may specify multiple email verification configurations if you have more
    | than one user table or model in the application and you want to have
    | separate email verification settings based on the specific user types.
    |
    | The expire time is the number of minutes that the reset token should be
    | considered valid. This security feature keeps tokens short-lived so
    | they have less time to be guessed. You may change this as needed.
    |
    */

    'verification' => [
        'users' => [
            'provider' => 'users',
            'table' => 'email_verifications',
            'expire' => 3600*7, //one week
        ],
    ],

    /*
     * Flags to determine whether the application should check
     * if a user is verified and/or activated
     * The first two are for web usage (Session Guard), the latters
     * are for api usage (Token Guard)
     *
     */
    'check_verification' => env('AUTH_CHECK_VERIFICATION',true),
    'check_activation' => env('AUTH_CHECK_ACTIVATION',true),

    'api_check_verification' => env('AUTH_API_CHECK_VERIFICATION',true),
    'api_check_activation' => env('AUTH_API_CHECK_ACTIVATION',true),

    /*
     * Default verification and activation values for users
     */

    'verification-value' => env('AUTH_VERIFICATION_VALUE',false),
    'activation-value' => env('AUTH_ACTIVATION_VALUE',true),

];
