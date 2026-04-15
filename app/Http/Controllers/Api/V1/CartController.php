<?php

namespace App\Http\Controllers\Api\V1;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Package;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    public function addToCart(Request $request)
    {
        // Validate input
        $validator = Validator::make($request->all(), [
            'cartable_type' => ['required', 'string', 'in:service,package'],
            'cartable_id' => ['required', 'integer'],
            'booking_date' => ['required', 'date', 'after_or_equal:today'],
            'time_slot_id' => ['required', 'integer', 'exists:time_slots,id'],
        ]);

        if ($validator->fails()) {
            return ResponseHelper::error(
                data: $validator->errors()->all(),
                message: 'Validation failed',
                statusCode: 422
            );
        }

        // Package or service
        $cartableType = $request->cartable_type === 'service' ? 
            Service::findOrFail($request->cartable_id) : 
            Package::with('services')->findOrFail($request->cartable_id);

        // Check if service/package is available for the selected time slot and date
        $cartService = new \App\Services\CartService();
        
        if ($request->cartable_type === 'service') {
            $available = $cartService->checkServiceIsAvailable(
                $request->cartable_id,
                $request->booking_date,
                $request->time_slot_id
            );
        } else {
            $packageServices = $cartableType->services;
            
            $available = $packageServices->every(function ($service) use ($request, $cartService) {
                return $cartService->checkServiceIsAvailable(
                    $service->id,
                    $request->booking_date,
                    $request->time_slot_id
                );
            });
        }

        if (!$available) {
            return ResponseHelper::error(
                message: 'The selected service/package is not available for the chosen date and time slot',
                statusCode: 400
            );
        }

        $cart = Cart::firstOrCreate([
            'user_id' => auth()->id(),
            'customer_name' => auth()->user()->name,
        ]);

        $cartItem = $cart->items()->create([
            'cartable_type' => get_class($cartableType),
            'cartable_id' => $request->cartable_id,
            'unit_price' => $cartableType->price,
            'time_slot_id' => $request->time_slot_id,
            'booking_date' => $request->booking_date,
        ]);

        $cart->update(['total_price' => $cart->total_price + $cartItem->unit_price]);

        return ResponseHelper::success(
            statusCode: 201,
            message: 'Item added to cart successfully'
        );
    }
}