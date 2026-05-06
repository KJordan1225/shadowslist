<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Business extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'slug',
        'description',
        'phone',
        'email',
        'website',
        'logo',
        'cover_photo',
        'address',
        'city',
        'state',
        'zip',
        'service_radius',
        'years_in_business',
        'licensed',
        'insured',
        'background_checked',
        'featured',
        'avg_rating',
        'review_count',
        'status',
    ];

    protected $casts = [
        'licensed' => 'boolean',
        'insured' => 'boolean',
        'background_checked' => 'boolean',
        'featured' => 'boolean',
        'avg_rating' => 'decimal:2',
        'review_count' => 'integer',
    ];

    protected static function booted(): void
    {
        static::creating(function (Business $business) {
            if (! $business->slug) {
                $business->slug = Str::slug($business->name) . '-' . uniqid();
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class)
            ->withTimestamps();
    }

    public function photos()
    {
        return $this->hasMany(BusinessPhoto::class);
    }

    public function primaryPhoto()
    {
        return $this->hasOne(BusinessPhoto::class)
            ->where('is_primary', true);
    }

    public function hours()
    {
        return $this->hasMany(BusinessHour::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function approvedReviews()
    {
        return $this->hasMany(Review::class)
            ->where('status', 'approved');
    }

    public function quotes()
    {
        return $this->hasMany(Quote::class);
    }

    public function favoritedBy()
    {
        return $this->belongsToMany(
            User::class,
            'favorites',
            'business_id',
            'user_id'
        )->withTimestamps();
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active');
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('featured', true);
    }

    public function scopeWithCategory(Builder $query, mixed $categoryId): Builder
    {
        return $query->whereHas('categories', function ($q) use ($categoryId) {
            $q->where('categories.id', $categoryId);
        });
    }

    public function recalculateRating(): void
    {
        $approvedReviews = $this->approvedReviews();

        $this->update([
            'avg_rating' => round((float) $approvedReviews->avg('rating'), 2),
            'review_count' => $approvedReviews->count(),
        ]);
    }
}
