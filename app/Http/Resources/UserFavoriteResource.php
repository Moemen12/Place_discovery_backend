<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserFavoriteResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'slug' => $this->slug,
            'title' => $this->title,
            'created_at' => $this->created_at,
            'favorite_users_count' => $this->favoritedByUsers()->count(),
            'image_url' => $this->whenLoaded('images', function () {
                $firstImage = $this->images->first();
                return $firstImage ? $firstImage->image_url : null;
            }),
        ];
    }
}
