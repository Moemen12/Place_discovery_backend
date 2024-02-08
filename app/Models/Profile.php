<?php

namespace App\Models;

use App\Traits\HandlesBase64Image;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;
    use HandlesBase64Image;

    protected $fillable = ['user_id', 'device_info'];

    protected $casts = [
        'device_info' => 'array',
    ];




    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function image()
    {
        return $this->hasOne(Image::class);
    }

    public function updateProfileImage($base64Image, $storagePath)
    {
        return $this->saveBase64Image($this, $base64Image, $storagePath);
    }
}
