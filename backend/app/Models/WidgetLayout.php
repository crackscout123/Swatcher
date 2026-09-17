<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Stores WHERE and HOW a widget is displayed on a dashboard grid.
 * Separated from DashboardWidget (WHAT is shown) intentionally.
 *
 * Grid uses a 12-column system (react-grid-layout / vue-grid-layout convention).
 */
class WidgetLayout extends Model
{
    use HasFactory;

    protected $fillable = [
        'dashboard_id',
        'dashboard_widget_id',
        'x',   // column position (0-11)
        'y',   // row position
        'w',   // width in columns
        'h',   // height in rows
        'min_w',
        'min_h',
    ];

    protected $casts = [
        'x' => 'integer',
        'y' => 'integer',
        'w' => 'integer',
        'h' => 'integer',
        'min_w' => 'integer',
        'min_h' => 'integer',
    ];

    public function widget()
    {
        return $this->belongsTo(DashboardWidget::class, 'dashboard_widget_id');
    }

    public function dashboard()
    {
        return $this->belongsTo(Dashboard::class);
    }
}
