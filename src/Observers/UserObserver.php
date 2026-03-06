<?php

namespace Ades4827\Sprintflow\Observers;

use Ades4827\Sprintflow\Models\User;
use Ades4827\Sprintflow\Models\Admin;
use Illuminate\Support\Facades\Cache;

class UserObserver
{
    public function creating(User $user)
    {
        if ($user instanceof Admin) return;
        $user->complete_name = $user->name_formatted;
    }

    public function updating(User $user)
    {
        if ($user instanceof Admin) return;
        $user->complete_name = $user->name_formatted;
    }

    public function updated(User $user)
    {
        if ($user instanceof Admin) return;
        Cache::forget('users_'.$user->id);
    }

    public function deleted(User $user)
    {
        if ($user instanceof Admin) return;
        $user->update([
            'email' => time().User::DELETE_TOKEN.$user->email,
        ]);
    }

    public function restored(User $user)
    {
        if ($user instanceof Admin) return;
        $email = explode(User::DELETE_TOKEN, $user->email);
        $user->update([
            'email' => $email[1],
        ]);
    }

    public function forceDeleted(User $user)
    {
        //
    }
}
