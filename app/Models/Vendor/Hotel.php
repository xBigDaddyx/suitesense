<?php

namespace App\Models\Vendor;

use App\Models\Role;
use App\Models\Room;
use App\Models\User;
use Filament\Models\Contracts\HasAvatar;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Wildside\Userstamps\Userstamps;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Hotel extends Model implements HasAvatar
{
    use SoftDeletes;
    use Userstamps;


    protected $fillable = [
        'logo_url',
        'name',
        'address',
        'country',
        'province',
        'district',
        'subdistrict',
        'postal_code',
        'city',
        'phone',
        'deleted_by'
    ];
    public function getFilamentAvatarUrl(): ?string
    {
        return '/storage//' . $this->logo_url;
    }
    public function rooms(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Room::class);
    }
    public function users(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(User::class, 'hotel_user');
    }
    public function members(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(User::class, 'hotel_user')->withPivot('department', 'job_title')->withTimestamps();
    }
    public function subscriptions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Subscription::class);
    }
    public function licenses(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(License::class);
    }
    public function roles(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Role::class, 'team_id');
    }
    public function getCurrentTenantLabel(): string
    {
        return 'Active hotel';
    }
}
