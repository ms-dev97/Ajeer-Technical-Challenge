<?php

namespace App\Http\Controllers\Api\V1;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        if ($validator->fails()) {
            return ResponseHelper::error(
                data: $validator->errors()->all(),
                message: 'Validation failed',
                statusCode: 422
            );
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
        ]);

        return ResponseHelper::success(message: 'User registered successfully', statusCode: 201);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email'],
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return ResponseHelper::error(
                data: $validator->errors()->all(),
                message: 'Validation failed',
                statusCode: 422
            );
        }

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return ResponseHelper::error(
                data: ['email' => ['These credentials do not match our records.']],
                message: 'Invalid credentials',
                statusCode: 401
            );
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return ResponseHelper::success(data: ['access_token' => $token], message: 'Login successful');
    }
}