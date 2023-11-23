<?php

namespace App\Listeners;

use App\Events\CreateTripEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Pusher\Pusher;

class CreateTripEventListener implements ShouldQueue
{

    public function handle(CreateTripEvent $event)
    {
        $trip = $event->trip;
        $user = $event->user;

        // Create a new Pusher instance
        $pusher = new Pusher(
            env('PUSHER_APP_KEY'),
            env('PUSHER_APP_SECRET'),
            env('PUSHER_APP_ID'),
            [
                'cluster' => env('PUSHER_APP_CLUSTER'),
                'useTLS' => true,
            ]
        );

        // Trigger the event on the 'drivers' channel
        $pusher->trigger('drivers', 'CreateTripEvent', [
            'trip' => $trip,
            'user' => $user,
        ]);
    }
}
