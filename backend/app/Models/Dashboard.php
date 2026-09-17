<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dashboard extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'description',
        'is_default',
        'variables',    // JSON: filter variables like selected server, time range
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'variables'  => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function widgets()
    {
        return $this->hasMany(DashboardWidget::class);
    }

    public function layout()
    {
        return $this->hasMany(WidgetLayout::class);
    }

    public function customCss()
    {
        return $this->hasOne(UiCustomCss::class);
    }
}
