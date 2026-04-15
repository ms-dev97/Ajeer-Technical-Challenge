<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = [
            ['name' => 'Cleaning', 'description' => 'Professional cleaning services for your home or office.', 'price' => 29.99, 'duration_in_minutes' => 60, 'capacity_per_slot' => 2],
            ['name' => 'Plumbing', 'description' => 'Expert plumbing services for all your needs.', 'price' => 39.99, 'duration_in_minutes' => 90, 'capacity_per_slot' => 1],
            ['name' => 'Electrical', 'description' => 'Skilled electricians for repairs and installations.', 'price' => 20.58, 'duration_in_minutes' => 120, 'capacity_per_slot' => 1],
            ['name' => 'Gardening', 'description' => 'Lawn care and gardening services to keep your outdoor space beautiful.', 'price' => 15.57, 'duration_in_minutes' => 180, 'capacity_per_slot' => 1],
            ['name' => 'Painting', 'description' => 'High-quality painting services for interior and exterior projects.', 'price' => 50.63, 'duration_in_minutes' => 240, 'capacity_per_slot' => 3],
        ];

        foreach ($services as $service) {
            Service::create($service);
        }
    }
}
