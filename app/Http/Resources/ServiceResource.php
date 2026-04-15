<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'duration_in_minutes' => $this->duration_in_minutes,
            'capacity_per_slot' => $this->capacity_per_slot,
            'is_active' => $this->is_active,
        ];
    }
}
