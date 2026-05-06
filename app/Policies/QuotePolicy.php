<?php

namespace App\Policies;

use App\Models\Quote;
use App\Models\User;

class QuotePolicy
{
    public function before(User $user): ?bool
    {
        return $user->isAdmin() ? true : null;
    }

    public function accept(User $user, Quote $quote): bool
    {
        return $quote->serviceRequest
            && $quote->serviceRequest->user_id === $user->id;
    }
}
