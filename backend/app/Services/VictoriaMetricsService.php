<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

/**
 * Service for reading from and writing to VictoriaMetrics.
 * Uses the Prometheus-compatible HTTP API.
 */
class VictoriaMetricsService
{
    protected string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = rtrim(config('services.victoriametrics.url', 'http://victoriametrics:8428'), '/');
    }

    /**
     * Instant query — returns the current value of a MetricsQL expression.
     */
    public function query(string $expr, ?string $time = null): array
    {
        $response = Http::get("{$this->baseUrl}/api/v1/query", array_filter([
            'query' => $expr,
            'time'  => $time,
        ]));

        return $response->json();
    }

    /**
     * Range query — returns values over a time range.
     */
    public function queryRange(string $expr, string $start, string $end, string $step = '60s'): array
    {
        $response = Http::get("{$this->baseUrl}/api/v1/query_range", [
            'query' => $expr,
            'start' => $start,
            'end'   => $end,
            'step'  => $step,
        ]);

        return $response->json();
    }

    /**
     * Push metrics via remote_write compatible endpoint.
     */
    public function pushMetrics(string $metricsText): bool
    {
        $response = Http::withBody($metricsText, 'text/plain')
            ->post("{$this->baseUrl}/api/v1/import/prometheus");

        return $response->successful();
    }

    /**
     * Health check.
     */
    public function isHealthy(): bool
    {
        try {
            return Http::get("{$this->baseUrl}/-/healthy")->successful();
        } catch (\Throwable) {
            return false;
        }
    }
}
