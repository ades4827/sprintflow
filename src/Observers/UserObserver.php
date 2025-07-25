<?php

namespace Ades4827\Sprintflow\Observers;

use Ades4827\Sprintflow\Models\User;
use Illuminate\Support\Facades\Cache;

class UserObserver
{
    /**
     * Handle the User "created" event.
     *
     * @param  User  $user
     * @return void
     */
    public function created(User $user)
    {
        $user->update([
            'complete_name' => $user->name_formatted,
        ]);
    }

    /**
     * Handle the User "updated" event.
     *
     * @param  User  $user
     * @return void
     */
    public function updated(User $user)
    {
        Cache::forget('users_'.$user->id);

        if ($user->complete_name != $user->name_formatted) {
            $user->complete_name = $user->name_formatted;
            $user->save();
        }
    }

    /**
     * Handle the User "deleted" event.
     *
     * @param  User  $user
     * @return void
     */
    public function deleted(User $user)
    {
        $user->update([
            'email' => time().User::DELETE_TOKEN.$user->email,
        ]);
    }

    /**
     * Handle the User "restored" event.
     *
     * @param  User  $user
     * @return void
     */
    public function restored(User $user)
    {
        $email = explode(User::DELETE_TOKEN, $user->email);
        $user->update([
            'email' => $email[1],
        ]);
    }

    /**
     * Handle the User "force deleted" event.
     *
     * @param  User  $user
     * @return void
     */
    public function forceDeleted(User $user)
    {
        //
    }
}
