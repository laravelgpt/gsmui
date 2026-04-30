<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Cashier\Billable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, Billable, HasRoles;

    protected $fillable = [
        'two_factor_secret',
        'two_factor_recovery_codes',
        'two_factor_enabled',
        'name',
        'email',
        'password',
        'role',
        'stripe_id',
        'pm_type',
        'pm_last_four',
        'trial_ends_at',
        'subscription_status',
        'email_verified_at'
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'stripe_id',
        'pm_type',
        'pm_last_four',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'trial_ends_at' => 'datetime',
        'subscription_status' => 'string',
        'role' => 'string',
        'two_factor_secret' => 'encrypted',
        'two_factor_recovery_codes' => 'encrypted',
        'two_factor_enabled' => 'boolean',
    ];

    protected $appends = ['has_active_subscription'];

    /** RELATIONSHIPS */

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    public function components()
    {
        return $this->hasMany(Component::class, 'created_by');
    }

    public function templates()
    {
        return $this->hasMany(Template::class, 'created_by');
    }

    /** ATTRIBUTES */

    protected function regenerateSession()
    {
        session()->regenerate();
    }

    public function getHasActiveSubscriptionAttribute()
    {
        return $this->subscription_status === 'active' && 
               $this->subscribed('pro') && 
               !$this->subscription('pro')?->cancelled();
    }

    /** SCOPES */

    public function scopeAdmins($query)
    {
        return $query->where('role', 'admin');
    }

    public function scopeSubscribers($query)
    {
        return $query->where('subscription_status', 'active');
    }
}
