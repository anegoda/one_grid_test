<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    /**
     * Creator of post
     *
     * @return HasMany
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Who rated post
     *
     * @return BelongsToMany
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'post_ratings');
    }

    public function ratingValues(): HasMany
    {
        return $this->hasMany(PostRating::class);
    }

    public function isCreatedByUser(User $user): bool
    {
        return $this->user_id === $user->id;
    }

    public function isRatedByUser(User $user): bool
    {
        return $this->users()->where('user_id', $user->id)->exists();
    }
}
