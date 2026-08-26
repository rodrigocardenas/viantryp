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
            'monthly' => env('PADDLE_PRICE_ESENCIAL_MONTHLY', 'pri_01m0zk19sv3nwsv8atnbk72atm'),
            'annual'  => env('PADDLE_PRICE_ESENCIAL_ANNUAL',  'pri_01m0zk1aj9gg3kran1tjegjdzr'),
        ],
        'avanzado' => [
            'monthly' => env('PADDLE_PRICE_AVANZADO_MONTHLY', 'pri_01m0zk1b5fesd3b698qtrqaxff'),
            'annual'  => env('PADDLE_PRICE_AVANZADO_ANNUAL',  'pri_01m0zk1bsyqkj3txr6g1v4mzm2'),
        ],
        'colaborativo' => [
            'monthly' => env('PADDLE_PRICE_COLABORATIVO_MONTHLY', 'pri_01m0zk1cefxc8czzqwm8bbahsv'),
            'annual'  => env('PADDLE_PRICE_COLABORATIVO_ANNUAL',  'pri_01m0zk1d29sy4v3hp9m6nv9byd'),
        ],
    ],
];

