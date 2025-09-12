<?php

namespace App\Jobs;

use App\Models\Folder;
use App\Models\StorageConfig;
use Illuminate\Bus\Batch;
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
        public readonly StorageConfig $storageConfig,
    ) {}

    public function handle(): void
    {
        // Index the root folder of the storage config
        $rootFolder = Folder::firstOrCreate([
            'name' => '',
            'parent_id' => null,
            'storage_config_id' => $this->storageConfig->id,
        ]); // TODO: import timestamps from filesystem
        $this->storageConfig->root_folder_id = $rootFolder->id;
        $this->storageConfig->save();

        $storageConfig = $this->storageConfig;
        Bus::batch([new IndexFolderJob($rootFolder, true)])
            ->name('Indexing storage: '.$this->storageConfig->name)
            ->allowFailures()
            ->before(function () use ($storageConfig) {
                $storageConfig->update([
                    'status' => 'indexing',
                ]);
            })
            ->then(function (Batch $batch) use ($storageConfig) {
                $storageConfig->update([
                    'status' => $batch->hasFailures() ? 'error' : 'connected',
                    'last_indexed_at' => now(),
                ]);
            })
            ->dispatch();
    }
}
