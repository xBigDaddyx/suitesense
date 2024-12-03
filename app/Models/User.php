<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Models\Vendor\Customer;
use App\Models\Vendor\Hotel;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasAvatar;
use Filament\Models\Contracts\HasTenants;
use Filament\Panel;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Wildside\Userstamps\Userstamps;

class User extends Authenticatable implements FilamentUser, HasTenants, HasAvatar, MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use Notifiable;
    use HasFactory;
    use Userstamps;
    use HasRoles;
    use SoftDeletes;
    public static function boot()
    {
        parent::boot();
        // static::creating(function ($user) {
        //     $user->latestHotel()->associate(Hotel::find(1));
        //     $user->hotels()->attach(Hotel::find(1), ['department' => 'Vendor', 'job_title' => 'System Administrator']);
        // });
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
        return $this->belongsToMany(Hotel::class, 'hotel_user')->withPivot('department', 'job_title')->withTimestamps();
    }

    public function getTenants(Panel $panel): Collection
    {
        return $this->hotels;
    }

    public function canAccessTenant(Model $tenant): bool
    {
        return $this->hotels()->whereKey($tenant)->exists() && $this->hasVerifiedEmail();
    }
    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }
    public function getDefaultTenant(Panel $panel): ?Model
    {
        return $this->latestHotel;
    }

    public function latestHotel(): BelongsTo
    {
        return $this->belongsTo(Hotel::class, 'hotel_id');
    }
    public function customer(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Customer::class, 'email', 'email');
    }
    public function getFilamentAvatarUrl(): ?string
    {
        return $this->avatar_url;
    }
}
