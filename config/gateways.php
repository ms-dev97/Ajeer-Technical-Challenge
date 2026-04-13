<?php

return [
    'gateways' => [
        'stripe' => [
            'class' => App\Services\Payment\Gateways\StripeGateway::class,
            'enabled' => true,
            'allowed_cities' => ['*'],
            'allowed_modules' => ['maintenance'],
        ],
    ],
];

