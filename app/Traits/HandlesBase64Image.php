<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;

trait HandlesBase64Image
{
    /**
     * Save a base64-encoded image to the server, delete the old one, and update the database record.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param string $base64Image
     * @param string $storagePath
     * @return string|bool
     */
    public function saveBase64Image($model, $base64Image, $storagePath)
    {
        // Decode base64 image
        $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $base64Image));

        // Delete old image if it exists
        if ($model->image && $model->image->image_url) {
            $fullPath = 'public/' . $model->image->image_url;
            Storage::delete($fullPath);
        }

        // Save image to server
        $fileName = uniqid() . '.webp';
        $filePath = '/' . $storagePath . '/' . $fileName;
        Storage::put('public/' . $filePath, $imageData);
        $model->image()->where('image_for', 'profile')->update(['image_url' => $filePath]);

        return $fileName;
    }
}
