<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasTenants;
use Filament\Panel;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Wildside\Userstamps\Userstamps;

class User extends Authenticatable implements FilamentUser, HasTenants
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;
    use Userstamps;
    use HasRoles;
    public static function boot()
    {
        parent::boot();
        static::creating(function ($user) {
            $user->latestHotel()->associate(Hotel::find(1));
            $user->hotels()->attach(Hotel::find(1), ['department' => 'Vendor', 'job_title' => 'System Administrator']);
        });
    }
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
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
            'password' => 'hashed',
        ];
    }
    public function hotels(): BelongsToMany
    {
        return $this->belongsToMany(Hotel::class, 'hotel_user', 'user_id', 'hotel_id')->withPivot('department', 'job_title')->withTimestamps();
    }

    public function getTenants(Panel $panel): Collection
    {
        return $this->hotels;
    }

    public function canAccessTenant(Model $tenant): bool
    {
        return $this->hotels()->whereKey($tenant)->exists();
    }
    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }
    public function getDefaultTenant(Panel $panel): ?Model
    {
        return $this->latestHotel;
    }

    public function latestTeam(): BelongsTo
    {
        return $this->belongsTo(Hotel::class, 'hotel_id');
    }
}
