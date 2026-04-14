<?php

namespace App\Services\Payment\Gateways;

class PaypalGateway implements PaymentGatewayInterface
{
    public function processPayment(array $paymentData): array
    {
        // Simulate processing payment with PayPal
        return [
            'success' => true,
            'transaction_id' => 'paypal_' . uniqid(),
            'message' => 'Payment processed successfully with PayPal.',
            'status' => 'completed',
        ];
    }

    public function getName(): string
    {
        return 'PayPal';
    }
}