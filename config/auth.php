<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Defaults
    |--------------------------------------------------------------------------
    |
    | This option controls the default authentication "guard" and password
    | reset options for your application. You may change these defaults
    | as required, but they're a perfect start for most applications.
    |
    */

    'defaults' => [
        'guard' => 'web',
        'passwords' => 'users',
    ],

    /*
    |--------------------------------------------------------------------------
    | Authentication Guards
    |--------------------------------------------------------------------------
    |
    | Next, you may define every authentication guard for your application.
    | Of course, a great default configuration has been defined for you
    | here which uses session storage and the Eloquent user provider.
    |
    | All authentication drivers have a user provider. This defines how the
    | users are actually retrieved out of your database or other storage
    | mechanisms used by this application to persist your user's data.
    |
    | Supported: "session"
    |
    */

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],

        // 'api' => [
        //     'driver' => 'token',
        //     'provider' => 'users',
        //     'hash' => false,
        // ],
        'superadmin' => [
            'driver' => 'session',
            'provider' => 'admins',
        ],
        'admin' => [
            'driver' => 'session',
            'provider' => 'admins',
        ],
        'jobseeker' => [
            'driver' => 'session',
            'provider' => 'jobseekers',
        ],
         'recruiter' => [
            'driver' => 'session',
            'provider' => 'recruiters',
        ], 
        'trainer' => [
            'driver' => 'session',
            'provider' => 'trainers',
        ], 
        'assessor' => [
            'driver' => 'session',
            'provider' => 'assessors',
        ], 
        'coach' => [
            'driver' => 'session',
            'provider' => 'coaches',
        ], 
        'mentor' => [
            'driver' => 'session',
            'provider' => 'mentors',
        ],
        'expat' => [
            'driver' => 'session',
            'provider' => 'jobseekers',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | User Providers
    |--------------------------------------------------------------------------
    |
    | All authentication drivers have a user provider. This defines how the
    | users are actually retrieved out of your database or other storage
    | mechanisms used by this application to persist your user's data.
    |
    | If you have multiple user tables or models you may configure multiple
    | sources which represent each model / table. These sources may then
    | be assigned to any extra authentication guards you have defined.
    |
    | Supported: "database", "eloquent"
    |
    */

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => App\Models\User::class,
        ],
        'admins' => [
            'driver' => 'eloquent',
            'model' => App\Models\Admin::class,
        ],
        'jobseekers' => [
            'driver' => 'eloquent',
            'model' => App\Models\Jobseekers::class,
        ],
        'recruiters' => [
            'driver' => 'eloquent',
            'model' => App\Models\RecruiterCompany::class,
        ],
        'trainers' => [
            'driver' => 'eloquent',
            'model' => App\Models\Trainers::class,
        ],
        'assessors' => [
            'driver' => 'eloquent',
            'model' => App\Models\Assessors::class,
        ],
        'coaches' => [
            'driver' => 'eloquent',
            'model' => App\Models\Coach::class,
        ],
        'mentors' => [
            'driver' => 'eloquent',
            'model' => App\Models\Mentors::class,
        ],
        'expats' => [
            'driver' => 'eloquent',
            'model' => App\Models\Jobseekers::class,
        ],

        // 'users' => [
        //     'driver' => 'database',
        //     'table' => 'users',
        // ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Resetting Passwords
    |--------------------------------------------------------------------------
    |
    | You may specify multiple password reset configurations if you have more
    | than one user table or model in the application and you want to have
    | separate password reset settings based on the specific user types.
    |
    | The expire time is the number of minutes that each reset token will be
    | considered valid. This security feature keeps tokens short-lived so
    | they have less time to be guessed. You may change this as needed.
    |
    */

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => 'password_resets',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Password Confirmation Timeout
    |--------------------------------------------------------------------------
    |
    | Here you may define the amount of seconds before a password confirmation
    | times out and the user is prompted to re-enter their password via the
    | confirmation screen. By default, the timeout lasts for three hours.
    |
    */

    'password_timeout' => 10800,

];
