<?php

return [
    'codes' => [
        'esencial' => env('PLAN_CODE_ESENCIAL'),
        'avanzado' => env('PLAN_CODE_AVANZADO'),
        'colaborativo' => env('PLAN_CODE_COLABORATIVO'),
        'corporativo' => env('PLAN_CODE_CORPORATIVO'),
    ],
    'request_email' => env('PLAN_REQUEST_EMAIL', 'hola@viantryp.com'),
    'paddle' => [
        'esencial' => [
            'monthly' => env('PADDLE_PRICE_ESENCIAL_MONTHLY', 'pri_01m126cmtzsjq689wyjffgvycp'),
            'annual'  => env('PADDLE_PRICE_ESENCIAL_ANNUAL',  'pri_01m126dgb09gsng19hcawr8bsg'),
        ],
        'avanzado' => [
            'monthly' => env('PADDLE_PRICE_AVANZADO_MONTHLY', 'pri_01m126evrhqajtf0z82kepgsy4'),
            'annual'  => env('PADDLE_PRICE_AVANZADO_ANNUAL',  'pri_01m126y3p9ewwcyrerv4ecmrbc'),
        ],
        'colaborativo' => [
            'monthly' => env('PADDLE_PRICE_COLABORATIVO_MONTHLY', 'pri_01m12701hthm2wrfarpgpmb3rn'),
            'annual'  => env('PADDLE_PRICE_COLABORATIVO_ANNUAL',  'pri_01m1271atn2p8wzqpnfessch38'),
        ],
    ],
];

