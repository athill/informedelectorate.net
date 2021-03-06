<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, SparkPost and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
    ],

    'ses' => [
        'key' => env('SES_KEY'),
        'secret' => env('SES_SECRET'),
        'region' => 'us-east-1',
    ],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'stripe' => [
        'model' => App\User::class,
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],


    //// my services
    'api' => [
        'datagov' => [
            'key' => env('API_DATA_GOV_KEY')
        ],

        'sunlight' => [
            'key' => env('SUNLIGHT_KEY')
        ],

        'openstates' => [
            'key' => env('OPEN_STATES_KEY')
        ],

        'google' => [
            'key' => env('GOOGLE_KEY')
        ], 
        'propublica' => [
            'key' => env('PROPUBLICA_KEY')
        ],                        
    ],

    'analytics' => [
        'google' => [
            'key' => env('GOOGLE_ANALYTICS_KEY', null)
        ],
    ]

];
