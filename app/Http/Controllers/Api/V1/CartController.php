<?php

namespace App\Http\Controllers\Api\V1;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingItem;
use App\Models\Cart;
use App\Models\Package;
use App\Models\Service;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    protected CartService $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

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

        return DB::transaction(function() use ($request) {
            // Package or service
            $cartableType = $request->cartable_type === 'service' ? 
                Service::findOrFail($request->cartable_id) : 
                Package::with('services')->findOrFail($request->cartable_id);

            // Check if service/package is available for the selected time slot and date
            if ($request->cartable_type === 'service') {
                $available = $this->cartService->isSlotAvailable(
                    $cartableType,
                    $cartableType->capacity_per_slot,
                    $request->booking_date,
                    $request->time_slot_id
                );

                if (! $available) {
                    return ResponseHelper::error(
                        message: 'The selected service is not available for the chosen date and time slot',
                        statusCode: 400
                    );
                }
            } else {
                $packageServices = $cartableType->services;

                foreach ($packageServices as $service) {
                    $available = $this->cartService->isSlotAvailable(
                        $service->id,
                        $service->capacity_per_slot,
                        $request->booking_date,
                        $request->time_slot_id
                    );

                    if (!$available) {
                        return ResponseHelper::error(
                            message: "The selected service (" . $service->name . ") is not available for the chosen date and time slot",
                            statusCode: 400
                        );
                    }
                }
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

            $cart->update(['total_price' => $cart->total_price + $cartItem->unit_price, 'status' => 'open']);

            return ResponseHelper::success(
                statusCode: 201,
                message: 'Item added to cart successfully'
            );
        });
    }

    public function checkout()
    {
        $cart = Cart::with('items')
            ->where('user_id', auth()->id())
            ->where('status', 'open')
            ->firstOrFail();

        if ($cart->items->isEmpty()) {
            return ResponseHelper::error(
                message: 'Your cart is empty',
                statusCode: 400
            );
        }

        DB::beginTransaction();

        try {
            $bookingItems = [];
            $booking = Booking::create([
                'user_id' => auth()->id(),
            ]);

            // Batch the booking items to for fast look up and bulk insert
            foreach ($cart->items as $item) {
                if ($item->cartable_type === Service::class) {
                    $bookingItems[$item->cartable_id] = [
                        'booking_date' => $item->booking_date,
                        'service_id' => $item->cartable_id,
                        'time_slot_id' => $item->time_slot_id,
                        'booking_id' => $booking->id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                } else {
                    $package = Package::with('services')->find($item->cartable_id);
                    foreach ($package->services as $service) {
                        $bookingItems[$service->id] = [
                            'booking_date' => $item->booking_date,
                            'service_id' => $service->id,
                            'time_slot_id' => $item->time_slot_id,
                            'booking_id' => $booking->id,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }
                }
            }

            // Check if the serivces available for the given timeslot and date
            $services = Service::whereIn('id', array_keys($bookingItems))->get()->keyBy('id');
            foreach($bookingItems as $id => $bookingItem) {
                $available = $this->cartService->isSlotAvailable(
                    $id,
                    $services->find($id)->capacity_per_slot,
                    $bookingItem['booking_date'],
                    $bookingItem['time_slot_id']
                );

                if (! $available) {
                    DB::rollBack();

                    return ResponseHelper::error(
                        message: 'The service (' . Service::find($bookingItem['service_id'])->name . ') is no longer available for the chosen date and time slot. Please adjust your cart and try again.',
                        statusCode: 400
                    );
                }
            }

            // Create services booking (Bulk insert)
            BookingItem::insert(array_values($bookingItems));

            $cart->items()->delete();
            $cart->update(['total_price' => 0, 'status' => 'checked_out']);

            DB::commit();

            return ResponseHelper::success(
                statusCode: 201,
                message: 'Checkout successful. Your cart has been cleared.'
            );
        } catch (\Throwable $th) {
            DB::rollBack();
            return ResponseHelper::error(
                message: 'An error occurred during checkout. Please try again.',
                statusCode: 500
            );
        }
    }
}