<?php

namespace Database\Seeders;

use App\Models\Package;
use App\Models\PackageService;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $packages = [
            ['name' => 'Basic', 'description' => 'Basic package.', 'price' => 49.99],
            ['name' => 'Standard', 'description' => 'Standard package.', 'price' => 79.99],
            ['name' => 'Premium', 'description' => 'Premium package.', 'price' => 129.99],
        ];

        foreach ($packages as $package) {
            Package::create($package);
        }

        // Attach services to packages
        PackageService::insert([
            ['package_id' => 1, 'service_id' => 1],
            ['package_id' => 1, 'service_id' => 2],
            ['package_id' => 2, 'service_id' => 2],
            ['package_id' => 2, 'service_id' => 3],
            ['package_id' => 3, 'service_id' => 3],
            ['package_id' => 3, 'service_id' => 4],
            ['package_id' => 3, 'service_id' => 5],
        ]);
    }
}
