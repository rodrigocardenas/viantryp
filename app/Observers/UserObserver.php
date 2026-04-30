<?php

namespace App\Observers;

use App\Models\User;
use App\Jobs\SyncUserRegistration;

class UserObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        SyncUserRegistration::dispatch($user);
    }
}
