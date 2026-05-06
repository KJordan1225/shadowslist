<?php

namespace App\Providers;

use App\Models\Business;
use App\Models\Message;
use App\Models\Quote;
use App\Models\Review;
use App\Models\ServiceRequest;
use App\Policies\BusinessPolicy;
use App\Policies\MessagePolicy;
use App\Policies\QuotePolicy;
use App\Policies\ReviewPolicy;
use App\Policies\ServiceRequestPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Gate::policy(Business::class, BusinessPolicy::class);
        Gate::policy(Review::class, ReviewPolicy::class);
        Gate::policy(ServiceRequest::class, ServiceRequestPolicy::class);
        Gate::policy(Quote::class, QuotePolicy::class);
        Gate::policy(Message::class, MessagePolicy::class);
    }
}
