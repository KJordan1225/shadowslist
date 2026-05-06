<?php

namespace App\Policies;

use App\Models\Review;
use App\Models\User;

class ReviewPolicy
{
    public function before(User $user): ?bool
    {
        return $user->isAdmin() ? true : null;
    }

    public function respond(User $user, Review $review): bool
    {
        return $review->business
            && $review->business->user_id === $user->id;
    }
}
