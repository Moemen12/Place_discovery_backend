<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserFavoriteResource;
use App\Models\User;
use App\Models\UserFavorite;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use Illuminate\Http\Response;


class UserFavoriteController extends Controller
{

    public function getUserFavoriteTrips()
    {
        $user = Auth::user();

        $user = User::findOrFail($user->id);
        $user->load('favoriteTrips.images');

        $favoriteTrips = UserFavoriteResource::collection($user->favoriteTrips);

        return $favoriteTrips;
    }


    public function addIntoUserFavorites(Request $request)
    {
        // $user_id = Auth::user();
        $validated = $request->validate([
            'user_id' => 'required',
            'trip_id' => 'required',
        ]);

        $user_id = $request->input('user_id');
        $trip_id = $request->input('trip_id');

        $result = UserFavorite::where('user_id', $user_id)
            ->where('trip_id', $trip_id)
            ->first();

        if ($result) {
            return response()->json([
                'error' => true,
                'message' => 'You have added this trip before'
            ], JsonResponse::HTTP_CONFLICT);
        }

        UserFavorite::create([
            'user_id' => $user_id,
            'trip_id' => $trip_id,
        ]);

        return response()->json([
            'message' => 'Added successfully'
        ], JsonResponse::HTTP_CREATED);
    }

    public function removeTripFromFavorites(int $trip_id)
    {
        $user = Auth::user();

        // Find the UserFavorite record based on user and trip IDs
        $favoriteRecord = UserFavorite::where('user_id', $user->id)
            ->where('trip_id', $trip_id)
            ->first();

        if (!$favoriteRecord) {
            return response()->json([
                'error' => true,
                'message' => 'Trip not found in favorites'
            ], Response::HTTP_NOT_FOUND);
        }

        $removedTrip = $favoriteRecord->delete();

        if ($removedTrip) {
            return response()->json([
                'message' => 'Removed successfully'
            ]);
        }

        return response()->json([
            'error' => true,
            'message' => 'An error occurred'
        ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
