<?php

namespace App\Listeners;

class LogUserLoginDate
{
    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $event->user->update(['last_login_at' => now()]);
    }
}
