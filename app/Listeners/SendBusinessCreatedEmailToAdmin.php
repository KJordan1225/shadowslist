<?php

namespace App\Listeners;

use App\Events\BusinessCreated;
use App\Mail\BusinessCreatedAdminNotification;
use Illuminate\Support\Facades\Mail;

class SendBusinessCreatedEmailToAdmin
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(BusinessCreated $event)
    {
        Mail::to(config('mail.admin_email'))
            ->send(new BusinessCreatedAdminNotification($event->business));
    }
}

