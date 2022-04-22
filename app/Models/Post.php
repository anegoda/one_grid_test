<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    const RATE_DOWN_VALUE = 0,
          RATE_UP_VALUE   = 1;


    protected $fillable = [
        'user_id',
        'title',
        'text',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'post_ratings');
    }

    public function ratingValues()
    {
        return $this->hasMany(PostRating::class);
    }
}
