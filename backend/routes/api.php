<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Server\ServerController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\WidgetController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Settings\GlobalSettingsController;
use App\Http\Controllers\Settings\UserPreferenceController;
use App\Http\Controllers\AlertRule\AlertRuleController;
use App\Http\Controllers\Metrics\MetricsController;

/*
|--------------------------------------------------------------------------
| Swatcher API Routes
|--------------------------------------------------------------------------
*/

// --- Public ---
Route::post('/login', [AuthController::class, 'login']);

// --- Authenticated ---
Route::middleware('auth:sanctum')->group(function () {

    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    // Servers
    Route::apiResource('servers', ServerController::class);
    Route::get('servers/{server}/metrics', [MetricsController::class, 'serverMetrics']);
    Route::get('servers/{server}/status', [MetricsController::class, 'serverStatus']);

    // Dashboards
    Route::apiResource('dashboards', DashboardController::class);
    Route::put('dashboards/{dashboard}/layout', [DashboardController::class, 'updateLayout']);

    // Widgets
    Route::apiResource('dashboards.widgets', WidgetController::class)->shallow();

    // Metrics (global)
    Route::get('metrics/query', [MetricsController::class, 'query']);

    // Alert Rules
    Route::apiResource('alerts', AlertRuleController::class);

    // User Preferences
    Route::get('preferences', [UserPreferenceController::class, 'show']);
    Route::put('preferences', [UserPreferenceController::class, 'update']);
    Route::put('preferences/css', [UserPreferenceController::class, 'updateCss']);
    Route::put('preferences/tokens', [UserPreferenceController::class, 'updateTokens']);

    // Admin only
    Route::middleware('can:view-admin-panel')->group(function () {
        Route::apiResource('users', UserController::class);
        Route::get('audit-log', [\App\Http\Controllers\Admin\AuditLogController::class, 'index']);
        Route::get('settings', [GlobalSettingsController::class, 'index']);
        Route::put('settings', [GlobalSettingsController::class, 'update']);
    });
});
