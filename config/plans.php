<?php

return [
    'codes' => [
        'explorador'   => env('PLAN_CODE_EXPLORADOR', env('PLAN_CODE_BASICO', '')),
        'básico'       => env('PLAN_CODE_EXPLORADOR', env('PLAN_CODE_BASICO', '')),
        'viajero_pro'  => env('PLAN_CODE_VIAJERO_PRO', env('PLAN_CODE_AVANZADO', '')),
        'viajero pro'  => env('PLAN_CODE_VIAJERO_PRO', env('PLAN_CODE_AVANZADO', '')),
        'avanzado'     => env('PLAN_CODE_VIAJERO_PRO', env('PLAN_CODE_AVANZADO', '')),
        'negocios'     => env('PLAN_CODE_NEGOCIOS', env('PLAN_CODE_COLABORATIVO', '')),
        'colaborativo' => env('PLAN_CODE_NEGOCIOS', env('PLAN_CODE_COLABORATIVO', '')),
        'corporativo'  => env('PLAN_CODE_CORPORATIVO', env('PLAN_CODE_NEGOCIOS', '')),
    ],
    'request_email' => env('PLAN_REQUEST_EMAIL', 'hola@viantryp.com'),
    'paddle' => [
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

