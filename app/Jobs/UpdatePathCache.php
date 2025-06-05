<?php

namespace App\Jobs;

use App\Models\Folder;
use App\Services\Cache\FolderCache;
use Illuminate\Contracts\Queue\ShouldBeUniqueUntilProcessing;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Bus;

class UpdatePathCache implements ShouldBeUniqueUntilProcessing, ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(readonly Folder $folder)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $cache = app(FolderCache::class);

        $cache->populateFolderIdCache($this->folder);
        $this->folder->loadMissing('folders');

        foreach ($this->folder->folders as $subFolder) {
            Bus::dispatchSync(new UpdatePathCache($subFolder));
        }
    }

    public function uniqueId(): string
    {
        return $this->folder->id;
    }
}
