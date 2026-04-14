<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\PaymentTransaction;
use App\Services\Payment\PaymentGatewayResolver;
use App\Helpers\ResponseHelper;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
    public function process(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'gateway_key' => 'required|string',
            'city' => 'required|string',
            'module' => 'required|string',
            'amount' => 'required|numeric|min:1',
        ]);
        
        if ($validator->fails()) {
            return ResponseHelper::error(
                data: $validator->errors()->all(),
                message: 'Validation failed',
                statusCode: 422
            );
        }

        // Resolve the payment gateway
        $paymentGatewayResolver = new PaymentGatewayResolver();
        $paymentGateway = $paymentGatewayResolver->resolve(
            $request->gateway_key,
            $request->city,
            $request->module
        );

        if (!$paymentGateway) {
            return ResponseHelper::error(message: 'Invalid payment gateway', statusCode: 400);
        }

        try {
            $paymentResult = $paymentGateway->processPayment($validator->validated());

            if (!$paymentResult['success']) {
                throw new \Exception('Payment processing failed: ' . $paymentResult['message']);
            }

            $transaction = PaymentTransaction::create([
                'user_id' => auth()->id(),
                'gateway' => $request->gateway_key,
                'amount' => $request->amount,
                'currency' => 'SAR',
                'status' => 'success',
                'city' => $request->city,
                'module' => $request->module,
                'gateway_transaction_id' => $paymentResult['transaction_id']
            ]);

            Log::info('Payment processed', [
                'transaction_id' => $transaction->id,
                'gateway' => $request->gateway_key,
                'amount' => $request->amount,
                'status' => $paymentResult['status'],
            ]);

            return ResponseHelper::success(data: $paymentResult, message: 'Payment processed successfully');
        } catch (\Exception $e) {
            Log::error('Payment processing failed', [
                'error' => $e->getMessage(),
                'gateway' => $request->gateway_key,
                'amount' => $request->amount,
            ]);

            return ResponseHelper::error(message: 'Payment processing failed', statusCode: 500);
        }
    }

    public function history(Request $request)
    {
        $transactions = PaymentTransaction::where('user_id', auth()->id())->orderBy('created_at', 'desc')->get();

        return ResponseHelper::success(data: $transactions, message: 'Payment history retrieved successfully');
    }
}