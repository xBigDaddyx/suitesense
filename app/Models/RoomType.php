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
        'hotel_id'
    ];
    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            if ($model->hotel_id == null) {
                $model->hotel_id = auth()->user()->hotel_id;
            }
        });
    }
    protected $casts = [
        'facilities' => 'array',
    ];
    public function rooms(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Room::class);
    }
}
