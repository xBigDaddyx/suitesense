<?php

namespace App\Models;

use App\Models\Vendor\Hotel;
use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    //
    public function hotel(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Hotel::class, 'team_id');
    }
}
