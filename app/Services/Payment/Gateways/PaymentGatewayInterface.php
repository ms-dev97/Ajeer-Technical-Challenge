<?php

namespace App\Services\Payment\Gateways;

interface PaymentGatewayInterface
{
    public function processPayment(array $paymentData): array;
    public function getName(): string;
}