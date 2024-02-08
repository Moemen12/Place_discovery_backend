<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Laravel\Scout\Searchable;

class Trip extends Model
{
    use HasFactory, Searchable;

    protected $fillable = ['user_id', 'description', 'slug', 'title', 'address', 'rating'];

    public static array $trip_place_type = ['hotel', 'camp', 'restaurant', 'entertainment', 'natural', 'archaeological'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function favoritedByUsers()
    {
        return $this->belongsToMany(User::class, 'user_favorites', 'trip_id', 'user_id');
    }

    public function images()
    {
        return $this->hasMany(Image::class);
    }

    public function reviews()
    {
        return $this->hasOne(Review::class);
    }
    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }
}
