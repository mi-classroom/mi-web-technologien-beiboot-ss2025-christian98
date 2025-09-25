<?php

namespace App\Console\Commands;

use App\Exceptions\Console\Commands\UnhealthyException;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Throwable;

class HealthCheckCommand extends Command
{
    protected $signature = 'app:healthcheck';

    protected $description = 'Runs a HealthCheck of the entire application';

    public function handle(): int
    {
        try {
            $this->checkHealth();

            return self::SUCCESS;
        } catch (Throwable $exception) {
            $this->error($exception->getMessage());

            return self::FAILURE;
        }
    }

    private function checkHealth(): void
    {
        $this->checkAppKey();
        $this->checkDBConnection();
        $this->checkSSRConnection();
    }

    /**
     * verify that the application has access to the application key
     */
    private function checkAppKey(): void
    {
        $missing = is_null(config('app.key'));

        if ($missing) {
            throw new UnhealthyException('AppKey missing!');
        }
    }

    private function checkDBConnection(): void
    {
        DB::connection()->getPdo();
    }

    private function checkSSRConnection(): void
    {
        /** @var array{url: string, enabled: bool} $ssrConfig */
        $ssrConfig = config('inertia.ssr');
        $inertiaHealthUrl = Str::of($ssrConfig['url'])
            ->replace('/render', '/health');

        if (! $ssrConfig['enabled']) {
            throw new UnhealthyException('Inertia SSR disabled');
        }

        try {
            $response = Http::get($inertiaHealthUrl);

            $isOk = $response->ok();
        } catch (Throwable $throwable) {
            throw new UnhealthyException(
                'Inertia SSR is unreachable: '.$throwable->getMessage(),
                previous: $throwable
            );
        }

        if (! $isOk) {
            throw new UnhealthyException('Inertia SSR endpoint is not ok');
        }
    }
}
