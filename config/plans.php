<?php

return [
    'codes' => [
        'explorador'   => env('PLAN_CODE_EXPLORADOR', env('PLAN_CODE_BASICO', '')),
        'básico'       => env('PLAN_CODE_EXPLORADOR', env('PLAN_CODE_BASICO', '')),
        'viajero_pro'  => env('PLAN_CODE_VIAJERO_PRO', env('PLAN_CODE_AVANZADO', env('PLAN_CODE_ESENCIAL', 'V7K2-MQ9X-E4NR,A3PH-XW8T-Z6LJ'))),
        'viajero pro'  => env('PLAN_CODE_VIAJERO_PRO', env('PLAN_CODE_AVANZADO', env('PLAN_CODE_ESENCIAL', 'V7K2-MQ9X-E4NR,A3PH-XW8T-Z6LJ'))),
        'avanzado'     => env('PLAN_CODE_VIAJERO_PRO', env('PLAN_CODE_AVANZADO', env('PLAN_CODE_ESENCIAL', 'V7K2-MQ9X-E4NR,A3PH-XW8T-Z6LJ'))),
        'esencial'     => env('PLAN_CODE_VIAJERO_PRO', env('PLAN_CODE_AVANZADO', env('PLAN_CODE_ESENCIAL', 'V7K2-MQ9X-E4NR,A3PH-XW8T-Z6LJ'))),
        'negocios'     => env('PLAN_CODE_NEGOCIOS', env('PLAN_CODE_COLABORATIVO', 'C9YD-RB5F-N2QK')),
        'colaborativo' => env('PLAN_CODE_NEGOCIOS', env('PLAN_CODE_COLABORATIVO', 'C9YD-RB5F-N2QK')),
        'corporativo'  => env('PLAN_CODE_CORPORATIVO', env('PLAN_CODE_NEGOCIOS', '')),
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

