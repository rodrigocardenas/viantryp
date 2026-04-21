<?php

return [
    'codes' => [
        'esencial' => env('PLAN_CODE_ESENCIAL'),
        'avanzado' => env('PLAN_CODE_AVANZADO'),
        'colaborativo' => env('PLAN_CODE_COLABORATIVO'),
        'corporativo' => env('PLAN_CODE_CORPORATIVO'),
    ],
    'request_email' => env('PLAN_REQUEST_EMAIL', 'hola@viantryp.com'),
];
