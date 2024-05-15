<?php

namespace App\Http\Resources;

use App\Models\Profile;
use App\Models\Rating;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TripResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */




    public function toArray(Request $request): array
    {

        $lastFivePeopleImages = $this->ratings()
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($rating) {
                return [
                    'id' => $rating->user?->profile?->image?->id,
                    'image_url' => $rating->user?->profile?->image?->image_url,
                ];
            });

        $allRatings = $this->ratings;

        $averageRating = $allRatings->isEmpty() ? 0 : $allRatings->avg('rating');
        if (fmod($averageRating, 1) == 0 && strpos($averageRating, '.0') === false && strpos($averageRating, '.5') === false) {
            $averageRatingFormatted = round($averageRating);
        } else {
            $averageRatingFormatted = rtrim(number_format($averageRating, 2), '0');
        }

        $reviews = $this->reviews?->get()->map((function ($review) {
            return [
                'id' => $review?->id,
                'review' => $review?->review,
                'profile_image' => $review?->user?->profile?->image?->image_url,
                'created_at' => $review?->created_at,
            ];
        }));


        return [
           
             'user_id'=>$this->user->id,
            'username' => $this->user->name,
            'slug' => $this->slug,
            'title' => $this->title,
            'description' => $this->description,
            'address' => $this->address,
            'created_at' => $this->created_at,
            'profile_image' => $this->user?->profile?->image?->image_url,
            'average_rating' => $averageRatingFormatted,
            'rating' => [
                'rating_people_count' => $this->ratings->count(),
                'lastFive_People_image' => $lastFivePeopleImages,
            ],
            'reviews' => $reviews ?? [],
            'images' => $this->images->map(function ($image) {
                return [
                    'id' => $image?->id,
                    'image_url' => $image?->image_url,
                ];
            }),
        ];
    }

    public function __construct($resource)
    {
        parent::__construct($resource);
    }
}
