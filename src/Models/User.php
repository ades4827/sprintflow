<?php

namespace Ades4827\Sprintflow\Models;

use Ades4827\Sprintflow\Casts\TrimCast;
use Ades4827\Sprintflow\Traits\BaseModelTrait;
use Ades4827\Sprintflow\Notifications\AdminNotification;
use Ades4827\Sprintflow\Notifications\NewUserEnabled;
use Ades4827\Sprintflow\Observers\UserObserver;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Lab404\Impersonate\Models\Impersonate;
use Laravel\Sanctum\HasApiTokens;
use Rappasoft\LaravelAuthenticationLog\Traits\AuthenticationLoggable;
use Spatie\Permission\Traits\HasRoles;
use Wildside\Userstamps\Userstamps;

#[ObservedBy([UserObserver::class])]
class User extends Authenticatable implements MustVerifyEmail
{
    use AuthenticationLoggable, BaseModelTrait, HasApiTokens, HasFactory, HasRoles, Impersonate, Notifiable, SoftDeletes, Userstamps;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'complete_name',
        'nickname',
        'name',
        'surname',
        'email',
        'password',
        'email_verified_at',
        'is_enabled',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'email_verified_at',
        'created_by',
        'updated_by',
        'deleted_by',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_enabled' => 'boolean',
        'name' => TrimCast::class,
        'surname' => TrimCast::class,
        'username' => TrimCast::class,
        'email' => TrimCast::class,
    ];

    public const DELETE_TOKEN = '::';

    public function getNameFormattedAttribute(): string
    {
        $name = [];
        if (!empty($this->surname) && !empty($this->name)) {
            $name[] = $this->surname;
            $name[] = $this->name;
            if (!empty($this->company)) {
                $name[] = ' (' . $this->company . ')';
            }
        }
        elseif (!empty($this->company)) {
            $name[] = $this->company;
        }
        $name = implode(', ', $name);

        //return strtoupper($name);
        return $name;
    }

    public function canRestore(): bool
    {
        $email = explode(self::DELETE_TOKEN, $this->email);

        if (! User::where('email', $email[1])->exists()) {
            return true;
        }

        return false;
    }

    public function canImpersonate(): bool
    {
        return $this->isAdmin();
    }

    public function isAdmin(): bool
    {
        if ($this->hasRole('admin')) {
            return true;
        }

        return false;
    }

    public function sendUserEnabledNotification(): void
    {
        $this->notify(new NewUserEnabled($this));
    }

    public function sendAdminNotification(string $notification_body, string $type = 'error'): void
    {
        $this->notify(new AdminNotification($notification_body, $type));
    }
}
