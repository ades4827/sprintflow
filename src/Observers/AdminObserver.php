<?php

namespace Ades4827\Sprintflow\Observers;

use Ades4827\Sprintflow\Models\Admin;
use Ades4827\Sprintflow\Models\User;
use Illuminate\Support\Facades\Cache;

class AdminObserver
{
    public function creating(Admin $admin)
    {
        $admin->complete_name = $admin->name_formatted;
    }

    public function updating(Admin $admin)
    {
        $admin->complete_name = $admin->name_formatted;
    }

    public function updated(Admin $admin)
    {
        Cache::forget('admins_'.$admin->id);
    }

    public function deleted(Admin $admin)
    {
        $admin->update([
            'email' => time(). User::DELETE_TOKEN.$admin->email,
        ]);
    }

    public function restored(Admin $admin)
    {
        $email = explode(User::DELETE_TOKEN, $admin->email);
        $admin->update([
            'email' => $email[1],
        ]);
    }

    public function forceDeleted(Admin $admin)
    {
        //
    }
}
