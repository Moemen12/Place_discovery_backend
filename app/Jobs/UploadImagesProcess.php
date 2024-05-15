<?php

namespace App\Jobs;

use App\Models\Trip;
use App\Models\Image;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class UploadImagesProcess implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
 protected $tripId;
    protected $imagesData;
   
      public function __construct($tripId, $imagesData)
    {
        $this->tripId = $tripId;
        $this->imagesData = $imagesData;
    }

    public function handle(): void
    {
          $trip = Trip::find($this->tripId);

        if ($trip) {
            foreach ($this->imagesData as $imageData) {
                // Decode the image content
                $decodedImage = base64_decode($imageData);

                // Generate a unique filename for each image
                $filename = uniqid() . '.webp';

                // Store the image in the storage using storeAs
                $filePath = '/images/trips/' . $filename;
                Storage::put('public/' . $filePath, $decodedImage);

                // Create an Image instance and associate it with the trip
                $image = new Image([
                    'image_url' => $filePath,
                    'image_for' => 'trip',
                ]);

                // Save the image with the trip association
                $trip->images()->save($image);
            }
        }
    }
}

