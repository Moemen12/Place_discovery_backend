<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\TripController;
use App\Http\Controllers\UserFavoriteController;
use App\Models\Trip;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/



Route::middleware(['api'])->group(function () {
    Route::post('/auth/register', [AuthController::class, 'register']);
    Route::post('/auth/login', [AuthController::class, 'login']);

    Route::get('/trip/{id}/{slug}/', [TripController::class, 'show']);

    Route::get('/trips', [TripController::class, 'index']);
});
Route::middleware('auth:sanctum')->group(function () {


    Route::post('/trip/create/review/', [ReviewController::class, 'add']);
    Route::post('/trip/create/new_trip', [TripController::class, 'create']);

    Route::get('/trips/types', function () {
        $user = Auth()->user();
        $trip_types = Trip::$trip_place_type;
        return new JsonResponse([
            'profile_image' => $user->profile->image->image_url,
            'trip_types' => $trip_types,
        ]);
    });
    Route::delete('/trips/delete/{trip_id}/saved', [UserFavoriteController::class, 'removeTripFromFavorites']);
    Route::put('/auth/user/profile', [ProfileController::class, 'updateUserProfile']);
    Route::get('/auth/user/profile', [ProfileController::class, 'getUserProfile']);
    Route::put('/auth/user/settings', [ProfileController::class, 'updateSettings']);
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::post('/trip/make/rating/', [RatingController::class, 'addRating']);
});
