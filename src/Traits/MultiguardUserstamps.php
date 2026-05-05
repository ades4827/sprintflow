<?php

namespace Ades4827\Sprintflow\Traits;

trait MultiguardUserstamps
{
    use \Mattiverse\Userstamps\Traits\Userstamps;

    protected function getUserClass(): string
    {
        // put this var in your model to access in cron
        if($this->userstamp_guard) {
            foreach (config('auth.guards') as $guard_name => $guard) {
                if ($guard_name === $this->userstamp_guard) {
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
