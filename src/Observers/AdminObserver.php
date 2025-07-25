<?php

namespace Ades4827\Sprintflow\Observers;

use Ades4827\Sprintflow\Models\Admin;
use Ades4827\Sprintflow\Models\User;
use Illuminate\Support\Facades\Cache;

class AdminObserver
{
    /**
     * Handle the Admin "created" event.
     *
     * @param  Admin  $admin
     * @return void
     */
    public function created(Admin $admin)
    {
        $admin->update([
            'complete_name' => $admin->name_formatted,
        ]);
    }

    /**
     * Handle the Admin "updated" event.
     *
     * @param  Admin  $admin
     * @return void
     */
    public function updated(Admin $admin)
    {
        Cache::forget('admins_'.$admin->id);

        if ($admin->complete_name != $admin->name_formatted) {
            $admin->complete_name = $admin->name_formatted;
            $admin->save();
        }
    }

    /**
     * Handle the Admin "deleted" event.
     *
     * @param  Admin  $admin
     * @return void
     */
    public function deleted(Admin $admin)
    {
        $admin->update([
            'email' => time().User::DELETE_TOKEN.$admin->email,
        ]);
    }

    /**
     * Handle the Admin "restored" event.
     *
     * @param  Admin  $admin
     * @return void
     */
    public function restored(Admin $admin)
    {
        $email = explode(User::DELETE_TOKEN, $admin->email);
        $admin->update([
            'email' => $email[1],
        ]);
    }

    /**
     * Handle the Admin "force deleted" event.
     *
     * @param  Admin  $admin
     * @return void
     */
    public function forceDeleted(Admin $admin)
    {
        //
    }
}
