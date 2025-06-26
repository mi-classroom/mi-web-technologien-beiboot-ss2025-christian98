<?php

namespace App\Jobs;

use App\Models\StorageConfig;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Bus;

class IndexStorageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        private readonly StorageConfig $storageConfig,
    ) {
    }

    public function handle(): void
    {
        // Index the root folder of the storage config
        $rootFolder = $this->storageConfig->rootFolder()->firstOrCreate([
            'name' => '',
        ]); // TODO: import timestamps from filesystem
        ray($rootFolder);

        Bus::dispatch(new IndexFolderJob($rootFolder), true);
    }
}
