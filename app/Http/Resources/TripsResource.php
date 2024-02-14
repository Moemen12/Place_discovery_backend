<?php

namespace App\Http\Resources;

use App\Models\Country;
use App\Models\Trip;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TripsResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_profile' => $this->user->profile->image?->image_url,
            'user_name' => $this->user->name,
            'slug' => $this->slug,
            'title' => $this->title,
            'address' => $this->address,
            'created_at' => $this->created_at,
            'images' => $this->formatImages($this->images),
        ];
    }

    protected function formatImages($images)
    {
        if ($images->isNotEmpty()) {
            $firstImage = $images->first();

            return [
                'image_url' => $firstImage->image_url,
            ];
        }

        return [];
    }

    public static function collection($resource)
    {
        $countryName = null;

        if (auth()->check()) {
            // User is logged in, fetch the country name
            $countryName = Country::where('user_id', auth()->user()->id)->pluck('country_name')->first();
        }

        $uniqueCountries = Trip::distinct()->pluck('address')->all();

        return [
            'user_preferred_country' => $countryName,
            'trips' => parent::collection($resource),
            'trip_types' => Trip::$trip_place_type,
            'countries' => $uniqueCountries,
        ];
    }
}
