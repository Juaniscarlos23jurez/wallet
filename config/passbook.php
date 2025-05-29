<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Passbook Configuration
    |--------------------------------------------------------------------------
    |
    | Here you can configure your Passbook settings.
    |
    */

    'certificates' => [
        'p12' => [
            'path' => env('PASSBOOK_P12_PATH', 'certificates/pass.p12'),
            'password' => env('PASSBOOK_P12_PASSWORD', ''),
        ],
        'wwdr' => [
            'path' => env('PASSBOOK_WWDR_PATH', 'certificates/wwdr.pem'),
        ],
    ],

    'pass' => [
        'type_identifier' => env('PASSBOOK_TYPE_IDENTIFIER', ''),
        'team_identifier' => env('PASSBOOK_TEAM_IDENTIFIER', ''),
        'organization_name' => env('PASSBOOK_ORGANIZATION_NAME', ''),
    ],

    'output' => [
        'path' => env('PASSBOOK_OUTPUT_PATH', 'storage/passes'),
    ],
]; 