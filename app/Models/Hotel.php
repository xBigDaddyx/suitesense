<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Wildside\Userstamps\Userstamps;

class Hotel extends Model
{
    use HasUuids;
    use SoftDeletes;
    use Userstamps;

    protected $fillable = [
        'name',
        'address',
        'country',
        'city',
        'phone',
        'deleted_by'
    ];
    public function rooms(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Room::class);
    }
    public function users(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(User::class, 'hotel_user');
    }
    public function getCurrentTenantLabel(): string
    {
        return 'Active hotel';
    }
}
