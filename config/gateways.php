<?php

return [
    'gateways' => [
        'stripe' => [
            'class' => App\Services\Payment\Gateways\StripeGateway::class,
            'enabled' => true,
            'allowed_cities' => ['*'],
            'allowed_modules' => ['maintenance'],
        ],
        'paypal' => [
            'class' => App\Services\Payment\Gateways\PaypalGateway::class,
            'enabled' => true,
            'allowed_cities' => ['*'],
            'allowed_modules' => ['maintenance'],
        ],
        'mada' => [
            'class' => App\Services\Payment\Gateways\MadaGateway::class,
            'enabled' => true,
            'allowed_cities' => ['Jeddah', 'Riyadh', 'Dammam', 'Mecca', 'Medina'],
            'allowed_modules' => ['maintenance', 'rent', 'utilities'],
        ],
    ]
];

