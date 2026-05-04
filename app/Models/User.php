<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Andreia\FilamentUiSwitcher\Models\Traits\HasUiPreferences;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Mattiverse\Userstamps\Traits\Userstamps;
use Spatie\Permission\Traits\HasRoles;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;

class User extends Authenticatable implements FilamentUser

{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles, HasUiPreferences, Userstamps, SoftDeletes;

    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_active',
        'store_setting_id',
        'selected_store',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'is_active'         => 'boolean',
            'ui_preferences'    => 'array',
            'selected_store'    => 'array',
        ];
    }

    public function storeSetting()
    {
        return $this->belongsTo(StoreSetting::class, 'store_setting_id');
    }
}
