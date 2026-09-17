<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DashboardWidget extends Model
{
    use HasFactory;

    // Widget types: stat | timeseries | status
    protected $fillable = [
        'dashboard_id',
        'type',
        'title',
        'query',          // PromQL / MetricsQL query string
        'server_id',      // optional: pin to specific server
        'config',         // JSON: widget-specific options
    ];

    protected $casts = [
        'config' => 'array',
    ];

    public function dashboard()
    {
        return $this->belongsTo(Dashboard::class);
    }

    public function server()
    {
        return $this->belongsTo(Server::class)->nullable();
    }

    public function layout()
    {
        return $this->hasOne(WidgetLayout::class);
    }
}
