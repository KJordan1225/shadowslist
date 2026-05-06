<?php

namespace App\Policies;

use App\Models\Message;
use App\Models\User;

class MessagePolicy
{
    public function before(User $user): ?bool
    {
        return $user->isAdmin() ? true : null;
    }

    public function view(User $user, Message $message): bool
    {
        return $user->id === $message->sender_id
            || $user->id === $message->recipient_id;
    }
}
