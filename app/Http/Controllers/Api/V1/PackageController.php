<?php

namespace App\Http\Controllers\Api\V1;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\PackageResource;
use App\Models\Package;

class PackageController extends Controller
{
    public function index()
    {
        $packages = Package::paginate(10);

        return $packages->toResourceCollection();
    }

    public function show(Package $package)
    {
        if (!$package) {
            return ResponseHelper::error(statusCode: 404);
        }

        $package->load('services');

        return new PackageResource($package);
    }
}