<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReviewRequest;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{


    public function add(ReviewRequest $reviewRequest)
    {
        $user_id = Auth::user()->id;

        $trip_id = $reviewRequest->validated('trip_id');
        $review = $reviewRequest->validated('review');

        $existingReview = Review::where('trip_id', $trip_id)
            ->where('user_id', $user_id)
            ->first();

        if ($existingReview) {

            return response()->json(['message' => 'You have already reviewed this trip.'], 422);
        }

        // If no existing review, proceed to create a new one
        $reviewData = [
            'trip_id' => $trip_id,
            'user_id' => $user_id,
            'review' => $review
        ];

        $createdReview = Review::create($reviewData);

        return response()->json($createdReview);
    }
}
