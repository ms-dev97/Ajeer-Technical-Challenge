<?php

namespace App\Services\Payment\Gateways;

class StripeGateway implements PaymentGatewayInterface
{
    public function processPayment(array $paymentData): array
    {
        // Simulate processing payment with Stripe
        return [
            'success' => true,
            'transaction_id' => 'stripe_' . uniqid(),
            'message' => 'Payment processed successfully with Stripe.',
        ];
    }

    public function getName(): string
    {
        return 'Stripe';
    }
}