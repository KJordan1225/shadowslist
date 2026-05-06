<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = [
        'business_id',
        'user_id',
        'rating',
        'quality_rating',
        'responsiveness_rating',
        'punctuality_rating',
        'professionalism_rating',
        'title',
        'body',
        'service_used',
        'service_date',
        'price_paid',
        'would_hire_again',
        'owner_response',
        'owner_responded_at',
        'status',
    ];

    protected $casts = [
        'service_date' => 'date',
        'owner_responded_at' => 'datetime',
        'would_hire_again' => 'boolean',
        'price_paid' => 'decimal:2',
    ];

    protected static function booted(): void
    {
        static::saved(function (Review $review) {
            $review->business?->recalculateRating();
        });

        static::deleted(function (Review $review) {
            $review->business?->recalculateRating();
        });
    }

    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }
}
