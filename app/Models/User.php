<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasUuids;



    protected $fillable = [
        'name',
        'email',
        'password',
        'bio',

    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    public function country()
    {
        return $this->hasOne(Country::class);
    }


    public function trips()
    {
        return $this->hasMany(Trip::class);
    }

    public function review()
    {
        return $this->hasOne(Review::class);
    }


    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = str_replace(' ', '', $value);
    }
}
