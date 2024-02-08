<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{



    protected function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:5|max:20',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|regex:/^[a-zA-Z0-9#@\$?]+$/|confirmed',
            'password_confirmation' => 'required|string'
        ]);

        if ($validator->stopOnFirstFailure()->fails()) {
            return response()->json([
                'error' => true,
                'message' => $validator->errors()->first(),
            ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }

        // Create the user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->country()->create([
            'country_name' => null
        ]);

        $deviceInfo = [
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'browser' => $request->header('User-Agent'),
            'platform' => $request->header('User-Agent'),
            'language' => $request->server('HTTP_ACCEPT_LANGUAGE'),
            'referrer' => $request->server('HTTP_REFERER'), // Get the referring URL
        ];


        $profile = Profile::create([
            'user_id' => $user->id,
            'device_info' => $deviceInfo,
        ]);

        $profile->image()->create([
            'image_for' => Image::$image_usages[0]
        ]);




        return response()->json(['message' => 'User registered successfully'], JsonResponse::HTTP_OK);
    }


    protected function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',

        ]);


        if ($validator->stopOnFirstFailure()->fails()) {

            return response()->json([
                'error' => true,
                'message' => $validator->errors()->first(),
            ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }


        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {

            $user = User::where('email', $credentials['email'])->first();

            $token = $user->createToken('api-token');


            $tripCount = $user->trips->count();


            return response()->json([
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'bio' => $user->bio,
                'published_trip_number' => $tripCount,
                'image' => $user->profile->image->image_url ?? null,
                'token' => $token->plainTextToken,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
            ], JsonResponse::HTTP_OK);
        }

        // Authentication failed
        return response()->json([
            'error' => true,
            'message' => 'Invalid credentials',
        ], JsonResponse::HTTP_UNAUTHORIZED);
    }


    protected function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Successfully logged out']);
    }
}
