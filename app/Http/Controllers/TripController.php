<?php

namespace App\Http\Controllers;

use App\Http\Requests\TripRequest;
use App\Http\Resources\TripResource;
use App\Http\Resources\TripsResource;
use App\Models\Country;
use App\Models\Image;
use App\Models\Trip;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


class TripController extends Controller
{

    public function index(Request $request)
    {

        // Your code to check authentication status
        $category = $request->query('category');
        $stars = $request->query('stars');
        $country = $request->query('country');

        //  Validate star rating
        if ($stars && !in_array($stars, [1, 2, 3, 4, 5])) {
            return response()->json(['error' => true, 'message' => 'Invalid star rating'], JsonResponse::HTTP_BAD_REQUEST);
        }

        // Base query
        $query = Trip::with('images')->latest('created_at');

        // Apply filters based on request parameters
        if ($stars) {
            $query->whereHas('ratings', function ($q) use ($stars) {
                $q->where('rating', $stars);
            });
        }

        if ($category) {
            $query->where('trip_type', $category);
        }

        if ($country) {
            // If country query exists, return only image_id and image_url
            $images = Image::select('id', 'image_url')
                ->where('image_for', 'trip')
                ->whereIn('trip_id', function ($query) use ($country) {
                    $query->select('id')->from('trips')->where('address', $country);
                })
                ->get();

            return response()->json([
                'images' => $images,
            ], JsonResponse::HTTP_OK);
        }

        // Get results
        $trips = $query->get();

        return response()->json([
            'trips' => TripsResource::collection($trips),
        ], JsonResponse::HTTP_OK);
    }




    public function AuthIndex(Request $request)
    {
        // Retrieve country name for the authenticated user
        $countryName = Country::where('user_id', auth()->user()->id)->pluck('country_name')->first();

        // Validate if country name is retrieved
        if (!$countryName) {
            // If no country is selected, return all trips with all countries
            $query = Trip::with('images')->latest('created_at');
        } else {
            // Validate star rating
            $stars = $request->query('stars');
            if ($stars && !in_array($stars, [1, 2, 3, 4, 5])) {
                return response()->json(['error' => true, 'message' => 'Invalid star rating'], JsonResponse::HTTP_BAD_REQUEST);
            }

            // Retrieve category from the request
            $category = $request->query('category');

            // Query trips table for trips with matching country name and category (if provided)
            $query = Trip::with('images')->latest('created_at')->where('address', $countryName);

            // Apply category filter if provided
            if ($category) {
                $query->where('trip_type', $category);
            }

            // Apply star rating filter if provided
            if ($stars) {
                $query->whereHas('ratings', function ($q) use ($stars) {
                    $q->where('rating', $stars);
                });
            }
        }

        // Get results
        $trips = $query->get();

        return response()->json([
            'trips' => TripsResource::collection($trips),
        ], JsonResponse::HTTP_OK);
    }






    /**
     * Show the form for creating a new resource.
     */
    public function create(TripRequest $tripRequest)
    {
        $validatedData = $tripRequest->validated();

        // Create the trip with basic details
        $trip = Trip::create([
            'title' => $validatedData['title'],
            'description' => $validatedData['description'],
            'trip_type' => $validatedData['trip_type'],
            'address' => $validatedData['address'],
            'user_id' => auth()->user()->id,
        ]);

        foreach ($validatedData['images'] as $imageData) {
            // Generate a unique filename for each image
            $filename = uniqid() . '.webp';

            // Store the image in the storage using storeAs
            $filePath = '/images/trips/' . $filename;
            Storage::putFileAs('public', $imageData, $filePath);

            // Create an Image instance and associate it with the trip
            $image = new Image([
                'image_url' => $filePath,
                'image_for' => 'trip',
            ]);

            // Save the image with the trip association
            $trip->images()->save($image);
        }

        return new JsonResponse($trip, JsonResponse::HTTP_CREATED);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id, string $slug)
    {
        $trip = Trip::with(['images'])
            ->where('id', $id)
            ->where('slug', $slug)
            ->first();

        if (!$trip) {
            return response()->json([
                'error' => 'Trip not found',
            ], JsonResponse::HTTP_NOT_FOUND);
        }

        return response()->json([
            'trip' => new TripResource($trip),
        ], JsonResponse::HTTP_OK);
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $trip = Trip::find($id);

        if (!$trip) {
            return new JsonResponse([
                'error' => 'Trip not found'
            ], 404);
        }

        if ($trip->user->id === auth()->user()->id) {
            $trip->delete();

            return new JsonResponse([
                'message' => 'Trip deleted successfully'
            ]);
        } else {
            return new JsonResponse([
                'error' => 'You are not authorized to delete this trip'
            ], 403);
        }
    }
}
