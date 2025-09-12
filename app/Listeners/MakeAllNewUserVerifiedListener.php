<?php

namespace App\Listeners;

use App\Models\User;
use Illuminate\Auth\Events\Registered;

/**
 * Listener to automatically mark all newly registered users as verified.
 *
 * @todo Remove this listener when email verification is implemented.
 */
class MakeAllNewUserVerifiedListener
{
    public function __construct() {}

    public function handle(Registered $event): void
    {
        $user = $event->user;
        if ($user instanceof User) {
            $user->markEmailAsVerified();
        }
    }
}
