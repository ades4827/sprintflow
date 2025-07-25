<?php

namespace Ades4827\Sprintflow\Models;

use Ades4827\Sprintflow\Notifications\AdminResetPassword;
use Ades4827\Sprintflow\Observers\AdminObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;

#[ObservedBy([AdminObserver::class])]
class Admin extends User
{
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new AdminResetPassword($token));
    }
}
