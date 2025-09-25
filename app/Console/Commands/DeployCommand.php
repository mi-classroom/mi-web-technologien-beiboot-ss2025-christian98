<?php

namespace App\Console\Commands;

use Closure;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use JetBrains\PhpStorm\ArrayShape;
use RuntimeException;
use Throwable;

class DeployCommand extends Command
{
    protected $signature = 'app:deploy {--no-info} {--migrate}';

    protected $description = 'This executes all necessary commands after doing a deployment.';

    public const string MIGRATE_LOCAL_NAME = 'lock-migrate';

    public function handle(): void
    {
        $this->migrate();

        $this->cacheBootstrapFiles();

        if (! $this->noInfo()) {
            $this->call('inspire');
            $this->call('about');
        }
    }

    protected function migrate(): void
    {
        if (! $this->shouldMigrate()) {
            return;
        }

        Cache::lock(self::MIGRATE_LOCAL_NAME)->get(function () {
            $this->call('down');
            $this->waitForDbConnection();
            $this->call('backup:run');
            $this->call('migrate', ['--force' => true]);
            $this->call('up');

            $this->call('sitemap:generate');
        });
    }

    protected function cacheBootstrapFiles(): void
    {
        $this->components->info('Caching the framework bootstrap files');

        /** @var Collection<int, array{description: string, task: Closure}> $cachingTasks */
        $cachingTasks = collect([
            $this->createTask('config', 'config:cache'),
            $this->createTask('routes', 'route:cache'),
            $this->createTask('views', 'view:cache'),
            $this->createTask('events', 'event:cache'),
        ]);

        $this->runTasks($cachingTasks);
    }

    /**
     * @return array{description: string, task: Closure}
     *
     * @note DO NOT CHANGE THE METHODS NAME UNLESS YOU ALSO CHANGE IT IN THE ide.json IN THE PROJECT ROOT!
     */
    #[ArrayShape(['description' => 'string', 'task' => Closure::class])]
    protected function createTask(string $description, string $commandName, array $commandParams = []): array
    {
        return [
            'description' => $description,
            'task' => fn () => $this->callSilent($commandName, $commandParams) === 0,
        ];
    }

    /**
     * @param  Collection<int, array{description: string, task: Closure}>  $cachingTasks
     */
    protected function runTasks(Collection $cachingTasks): void
    {
        $cachingTasks
            ->mapWithKeys(fn (array $item) => [$item['description'] => $item['task']])
            ->each(fn ($task, $description) => $this->components->task((string) $description, $task));

        $this->newLine();
    }

    protected function noInfo(): bool
    {
        return (bool) $this->option('no-info');
    }

    protected function shouldMigrate(): bool
    {
        return (bool) $this->option('migrate');
    }

    protected function waitForDbConnection(): void
    {
        $maxTimeout = 30;
        for ($i = 0; $i < $maxTimeout && ! $this->isDbUp(); $i++) {
            // sleep one second
            sleep(1);
        }

        if ($i >= $maxTimeout) {
            throw new RuntimeException('DB is not ready after 30 seconds. Aborting deployment');
        }
    }

    protected function isDbUp(): bool
    {
        try {
            DB::connection()->getPdo();

            return true;
        } catch (Throwable) {
            return false;
        }
    }
}
