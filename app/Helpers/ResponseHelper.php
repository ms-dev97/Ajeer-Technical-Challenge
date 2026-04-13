<?php

namespace App\Helpers;

class ResponseHelper
{
    public static function success($data = null, $message = null, $statusCode = 200)
    {
        $response = [
            'success' => true,
        ];

        if ($message) $response['message'] = $message;
        if ($data) $response['data'] = $data;

        return response()->json($response, $statusCode);
    }

    public static function error($data = null, $message = null, $statusCode = 400)
    {
        $response = [
            'success' => false,
        ];

        if ($message) $response['message'] = $message;
        if ($data) $response['data'] = $data;

        return response()->json($response, $statusCode);
    }
}