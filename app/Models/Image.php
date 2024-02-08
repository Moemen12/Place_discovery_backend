<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use PhpParser\Builder\FunctionLike;

class Image extends Model
{
    use HasFactory;

    protected $fillable = ['image_url', 'image_for'];
    public static $image_usages = ['profile', 'trip', 'review'];


    public function profile()
    {
        return $this->belongsTo(Profile::class);
    }

    public function review()
    {
        return $this->belongsTo(Review::class);
    }

    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }
}
