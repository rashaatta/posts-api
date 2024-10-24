<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\VerifyRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class  AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $user = User::create($request->validated());

        $verificationCode = rand(100000, 999999);
        // Log the code
        Log::info("Verification code for {$user->phone}: $verificationCode");

        $user->verification_code = $verificationCode;
        $user->save();
        $device = substr($request->userAgent() ?? '', 0, 255);
        $accessToken = $user->createToken($device)->plainTextToken;

        return response()->json(['data' => new UserResource($user), 'access_token' => $accessToken]);
    }

    public function login(LoginRequest $request)
    {
        $user = User::where('phone', $request->phone)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        if (!$user->is_verified) {
            return response()->json(['message' => 'Account not verified'], 403);
        }

        $device = substr($request->userAgent() ?? '', 0, 255);

        return response()->json([
            'data' => new UserResource($user),
            'access_token' => $user->createToken($device)->plainTextToken,
        ]);
    }

    public function verify(VerifyRequest $request)
    {
        $user = Auth::guard('api')->user();

        if (!$user) {
            return response()->json(['message' => 'Invalid verification code.'], 404);
        }

        if ($user->verification_code == $request->verification_code) {
            $user->is_verified = true;
            $user->save();

            return response()->json(['message' => 'Account verified']);
        }

        return response()->json(['message' => 'Invalid verification code'], 400);
    }


}
