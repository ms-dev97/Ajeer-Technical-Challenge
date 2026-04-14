<?php

namespace App\Services\Payment\Gateways;

class MadaGateway implements PaymentGatewayInterface
{
    public function processPayment(array $paymentData): array
    {
        // Simulate processing payment with Mada
        return [
            'success' => true,
            'transaction_id' => 'mada_' . uniqid(),
            'message' => 'Payment processed successfully with Mada.',
            'status' => 'completed',
        ];
    }

    public function getName(): string
    {
        return 'Mada';
    }
}