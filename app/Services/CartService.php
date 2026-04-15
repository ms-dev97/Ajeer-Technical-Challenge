<?php

namespace App\Services;

use App\Models\BookingItem;

class CartService
{
    public function isSlotAvailable($serviceId, $capacityPerSlot, $bookingDate, $timeSlotId)
    {
        $bookedCount = BookingItem::where([
            'service_id' => $serviceId,
            'booking_date' => $bookingDate,
            'time_slot_id' => $timeSlotId,
        ])
        ->lockForUpdate()
        ->count();

        return $bookedCount < $capacityPerSlot;
    }
}