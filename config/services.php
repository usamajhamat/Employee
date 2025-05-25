<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'tanaza' => [
        'api_url' => env('TANAZA_API_URL', 'https://cloud.tanaza.com/apis/v3.0/'),
        'ap_user_id' => env('TANAZA_AP_USER_ID'),
        'ap_token' => env('TANAZA_AP_TOKEN'),
        'ap_action' => env('TANAZA_AP_ACTION', 'status'),
        'company_code' => env('COMPANY_CODE', 'MAVEN'),
        'openwrt_status_minutes' => env('OPENWRT_STATUS_MINUTES', 5),
        'use_wifidog_to_update_ap_state' => env('USE_WIFIDOG_TO_UPDATE_AP_STATE', false),
    ],

];
