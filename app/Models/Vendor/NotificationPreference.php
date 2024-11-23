<?php

namespace App\Models\Vendor;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class NotificationPreference extends Model
{
    use HasUuids;
    protected $fillable = [
        'invoice_created',
        'invoice_reminder',
        'invoice_paid',
    ];
}
