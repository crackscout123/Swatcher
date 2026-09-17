<?php

namespace App\Jobs;

use App\Models\Server;
use App\Services\VictoriaMetricsService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class PollServerMetrics implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $timeout = 30;

    public function __construct(public readonly Server $server)
    {}

    public function handle(VictoriaMetricsService $vm): void
    {
        if (! $this->server->is_active) {
            return;
        }

        try {
            $metrics = $this->collectMetrics();
            $vm->pushMetrics($this->formatPrometheus($metrics));
        } catch (\Throwable $e) {
            Log::error("[Swatcher] PollServerMetrics failed for server {$this->server->id}", [
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Collect raw metrics from the server's configured metric source.
     * Supports: node_exporter (scrape HTTP) | local (exec commands)
     * SSH support planned for v1.1
     */
    private function collectMetrics(): array
    {
        return match ($this->server->metric_source) {
            'node_exporter' => $this->scrapeNodeExporter(),
            'local'         => $this->collectLocal(),
            default         => [],
        };
    }

    private function scrapeNodeExporter(): array
    {
        $url = $this->server->metric_source_url ?? "http://{$this->server->ip_address}:9100/metrics";
        // TODO: parse Prometheus text format and extract CPU/RAM/Disk/Load/Uptime
        return [];
    }

    private function collectLocal(): array
    {
        // TODO: exec system calls for local server metrics
        return [];
    }

    private function formatPrometheus(array $metrics): string
    {
        $lines = [];
        $labels = "server_id=\"{$this->server->id}\",server_name=\"{$this->server->name}\"";

        foreach ($metrics as $name => $value) {
            $lines[] = "swatcher_{$name}{{$labels}} {$value}";
        }

        return implode("\n", $lines);
    }
}
