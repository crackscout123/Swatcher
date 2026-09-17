<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AlertRule extends Model
{
    use HasFactory;

    protected $fillable = [
        'server_id',
        'name',
        'metric',        // cpu | ram | disk | load
        'operator',      // > | < | >= | <=
        'threshold',
        'for_minutes',   // alert fires only after x minutes above threshold
        'is_active',
        'notification_channel', // reserved for v1.1
    ];

    protected $casts = [
        'threshold'   => 'float',
        'for_minutes' => 'integer',
        'is_active'   => 'boolean',
    ];

    public function server()
    {
        return $this->belongsTo(Server::class);
    }
}
