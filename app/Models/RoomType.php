<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Wildside\Userstamps\Userstamps;

class RoomType extends Model
{
    use HasUuids;
    use SoftDeletes;
    use Userstamps;

    protected $fillable = [
        'name',
        'description',
        'facilities',
    ];
    protected $casts = [
        'facilities' => 'array',
    ];
    public function rooms(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Room::class);
    }
}
