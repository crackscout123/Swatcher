<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPreference extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'color_mode',       // light | dark | system
        'language',
        'default_dashboard_id',
        'ui_tokens',        // JSON: CSS design token overrides
        'custom_css',       // Scoped custom CSS string
        'polling_enabled',
        'notify_on_alert',
    ];

    protected $casts = [
        'ui_tokens'       => 'array',
        'polling_enabled' => 'boolean',
        'notify_on_alert' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
