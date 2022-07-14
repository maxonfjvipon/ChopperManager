<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Registered;

class AssignRoleToRegisteredUser
{
    /**
     * Handle the event.
     *
     * @return void
     */
    public function handle(Registered $event)
    {
        $event->user->assignRole('Client');
    }
}
