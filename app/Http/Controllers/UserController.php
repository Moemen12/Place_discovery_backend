<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Rating;
use App\Models\Trip;
use App\Models\User;
use Illuminate\Http\JsonResponse;



class UserController extends Controller
{
    public function getUsersInfo()
    {

        $user_count = User::count();
        $published_trips_count = Trip::Count();
        $country_count = Country::count();

        return new JsonResponse([
            'user_count' => $user_count,
            'published_trips_count' => $published_trips_count,
            'country_count' => $country_count
        ]);
    }

    public function getUserInfo($id, $username)
    {
        $user = User::where('id', $id)->where('name', $username)->first();
        if (!$user) {
            return new JsonResponse(['error' => 'User not found'], 404);
        }
        $profile_image = $user->profile->image->image_url;
        $published_trips_count = Trip::count();


        $average_rating = Rating::where('user_id', $id)->avg('rating');


        $formatted_rating = intval(number_format($average_rating, 0, '.', ''));
        $trips = Trip::where('user_id', $id)->get();
        $first_image = $trips->map(function ($trip) {
            $image = $trip->images->first();
            return [
                'trip_id' => $trip->id,
                'trip_slug' => $trip->slug,
                'trip_address' => $trip->address,
                'image_id' => $image->id,
                'image_url' => $image->image_url,
            ];
        });



        return new JsonResponse([
            'username' => $user->name,
            'join_date' => $user->created_at,
            'bio' => $user->bio,

            'profile_image' => $profile_image,
            'trip_count' => $published_trips_count,
            'global_rating' => $formatted_rating,
            'first_image_trip' => $first_image
        ]);
    }
}
