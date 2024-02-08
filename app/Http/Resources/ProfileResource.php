<?php

namespace App\Http\Resources;

use App\Models\Trip;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $publishedTripNum = $this->trips->count();

        return [
            'name' => $this->name,
            'country_name' => $this->country->country_name,
            'email' => $this->email,
            'published_trip_num' => $publishedTripNum,
            'bio' => $this->bio,
            'profile_image' => $this->profile->image?->image_url
        ];
    }
}
