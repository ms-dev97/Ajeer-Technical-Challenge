<?php

namespace App\Http\Controllers\Api\V1;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\ServiceResource;
use App\Models\Service;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::paginate(10);

        return $services->toResourceCollection();
    }

    public function show(Service $service)
    {
        if (!$service) {
            return ResponseHelper::error(statusCode: 404);
        }

        return new ServiceResource($service);
    }
}