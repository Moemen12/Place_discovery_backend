<?php

namespace App\Http\Controllers;

use App\Http\Requests\SettingRequest;
use App\Http\Requests\UpdateUserProfileRequest;
use App\Http\Resources\ProfileResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function getUserProfile()
    {
        $user = Auth::user();

        $user = User::with('trips', 'profile', 'country')->find($user->id);

        return new ProfileResource($user);
    }


    public function updateUserProfile(UpdateUserProfileRequest $request)
    {
        $user = User::find(auth()->user()->id);

        $validator = Validator::make($request->only(['bio', 'name', 'image_url']), $request->rules());

        if ($validator->stopOnFirstFailure()->fails()) {
            return response()->json([
                'error' => true,
                'message' => $validator->errors()->first(),
            ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }

        if (Gate::allows('update-profile', $user)) {
            $newImageName = null;

            if ($request->has('image_url')) {
                if ($request->image_url !== null && $request->image_url !== '') {
                    $newImageName = $user->profile->updateProfileImage($request->image_url, 'images/profile');
                } else {
                    $user->profile->image->where('image_for', 'profile')->update(['image_url' => null]);
                }
            }

            $user->name = $request->name;
            $user->bio = $request->bio;
            $user->save();

            return response()->json([
                'message' => 'Profile updated successfully',
                'new_image' => $newImageName,
            ], JsonResponse::HTTP_OK);
        } else {
            return response()->json(['error' => 'Unauthorized action'], JsonResponse::HTTP_FORBIDDEN);
        }
    }




    public function updateSettings(SettingRequest $request)
    {
        $user = User::find(auth()->user()->id);

        $user->country->update(['country_name' => $request->input('country_name')]);

        if ($request->filled('new_password') || $request->filled('current_password')) {
            $validator = Validator::make($request->all(), [
                'current_password' => 'required|string|min:8',
                'new_password' => 'required|string|min:8|regex:/^[a-zA-Z0-9#@\$?]+$/',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'error' => true,
                    'message' => $validator->errors()->first(),
                ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
            }

            $current_password = $request->input('current_password');

            if (!Hash::check($current_password, $user->password)) {
                return response()->json([
                    'error' => true,
                    'message' => 'Current password is incorrect',
                ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
            }

            $user->update([
                'password' => Hash::make($request->input('new_password')),
            ]);
        }

        return response()->json(['message' => 'Data updated successfully'], JsonResponse::HTTP_OK);
    }
}
