<?php

namespace Ades4827\Sprintflow\Traits;

trait MultiguardUserstamps
{
    use \Mattiverse\Userstamps\Traits\Userstamps;

    protected function getUserClass(?string $by_guard_name): string
    {
        if($by_guard_name) {
            foreach (config('auth.guards') as $guard_name => $guard) {
                if ($guard_name === $by_guard_name) {
                    return config('auth.providers.'.$guard['provider'].'.model', config('auth.providers.users.model'));
                }
            }
        }
        foreach (config('auth.guards') as $guard_name => $guard) {
            if (auth($guard_name)->check()) {
                return config('auth.providers.'.$guard['provider'].'.model', config('auth.providers.users.model'));
            }
        }
        return config('auth.providers.users.model');
    }
}
