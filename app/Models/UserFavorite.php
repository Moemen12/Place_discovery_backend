<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserFavorite extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'trip_id'];
    // public function users()
    // {
    //     return $this->belongsToMany(User::class);
    // }
}
