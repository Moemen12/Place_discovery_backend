<?php

namespace App\Http\Controllers;

use App\Http\Requests\RatingRequest;
use App\Models\Rating;
use App\Models\Trip;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;


class RatingController extends Controller
{
    public function addRating(RatingRequest $ratingRequest)
    {

        $user_id = Auth::user()->id;

        $rating = $ratingRequest->validated('stars_rating');
        $trip_id = $ratingRequest->validated('trip_id');

        $tripExists = Trip::where('id', $trip_id)->exists();
        if (!$tripExists) {
            return response()->json(['message' => 'The specified trip does not exist.'], 404);
        }


        $existingReview = Rating::where('trip_id', $trip_id)
            ->where('user_id', $user_id)
            ->first();

        if ($existingReview) {

            return response()->json(['message' => 'You have already rated this trip.'], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }

        Rating::create([
            'user_id' => $user_id,
            'trip_id' => $trip_id,
            'rating' => $rating,
        ]);

        return response()->json(['message' => 'Rating added successfully.']);
    }
}
