<?php

namespace Ades4827\Sprintflow\Traits;

trait MultiguardUserstamps
{
    use \Mattiverse\Userstamps\Traits\Userstamps;

    protected function getUserClass(): string
    {
        foreach (config('auth.guards') as $guard_name => $guard) {
            if (auth($guard_name)->check()) {
                return config('auth.providers.'.$guard['provider'].'.model', config('auth.providers.users.model'));
            }
        }
        return config('auth.providers.users.model');
    }
}
