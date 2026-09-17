<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Server extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'hostname',
        'ip_address',
        'port',
        'group',
        'description',
        'metric_source',   // node_exporter | ssh | local
        'metric_source_url',
        'poll_interval',   // seconds
        'is_active',
        'tags',
    ];

    protected $casts = [
        'is_active'  => 'boolean',
        'tags'       => 'array',
        'poll_interval' => 'integer',
    ];

    public function checks()
    {
        return $this->hasMany(ServerCheck::class);
    }

    public function lastCheck()
    {
        return $this->hasOne(ServerCheck::class)->latestOfMany();
    }

    public function alertRules()
    {
        return $this->hasMany(AlertRule::class);
    }
}
