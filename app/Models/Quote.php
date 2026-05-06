<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quote extends Model
{
    protected $fillable = [
        'service_request_id',
        'business_id',
        'amount',
        'message',
        'estimated_days',
        'status',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function serviceRequest()
    {
        return $this->belongsTo(ServiceRequest::class);
    }

    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    public function isAccepted(): bool
    {
        return $this->status === 'accepted';
    }
}
