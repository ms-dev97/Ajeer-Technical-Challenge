<?php

namespace App\Services;

use App\Models\BookingItem;

class CartService
{
    public function checkServiceIsAvailable($serviceId, $bookingDate, $timeSlotId)
    {
        $booked = BookingItem::where([
            'service_id' => $serviceId,
            'booking_date' => $bookingDate,
            'time_slot_id' => $timeSlotId,
        ])->exists();

        return !$booked;
    }
}