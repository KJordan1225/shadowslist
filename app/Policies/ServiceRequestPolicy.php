<?php

namespace App\Policies;

use App\Models\ServiceRequest;
use App\Models\User;

class ServiceRequestPolicy
{
    public function before(User $user): ?bool
    {
        return $user->isAdmin() ? true : null;
    }

    public function view(User $user, ServiceRequest $serviceRequest): bool
    {
        if ($user->id === $serviceRequest->user_id) {
            return true;
        }

        return $user->isProvider();
    }

    public function update(User $user, ServiceRequest $serviceRequest): bool
    {
        return $user->id === $serviceRequest->user_id;
    }

    public function delete(User $user, ServiceRequest $serviceRequest): bool
    {
        return $user->id === $serviceRequest->user_id;
    }
}
