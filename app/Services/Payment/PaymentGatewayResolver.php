<?php

namespace App\Services\Payment;

use App\Services\Payment\Gateways\PaymentGatewayInterface;

class PaymentGatewayResolver
{
    public function resolve(string $gatewayKey, string $city, string $module): PaymentGatewayInterface | null
    {
        $gatewaysConfig = config('gateways.gateways');

        if (!isset($gatewaysConfig[$gatewayKey])) {
            return null;
        }

        $gatewayConfig = $gatewaysConfig[$gatewayKey];

        if (!$gatewayConfig['enabled']) {
            return null;
        }

        if (!in_array('*', $gatewayConfig['allowed_cities']) && !in_array($city, $gatewayConfig['allowed_cities'])) {
            return null;
        }

        if (!in_array('*', $gatewayConfig['allowed_modules']) && !in_array($module, $gatewayConfig['allowed_modules'])) {
            return null;
        }

        return app($gatewayConfig['class']);
    }
}