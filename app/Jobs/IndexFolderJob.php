<?php

namespace App\Jobs;

use App\Models\Folder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class IndexFolderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(private readonly Folder $folder)
    {
    }

    public function handle(): void
    {
        // TODO Detect all files in the storage folder

        // Dispatch IndexFolderJob for subfolders
        foreach ($this->folder->folders as $subfolder) {
            self::dispatch($subfolder);
        }

        // Dispatch IndexFileJob for each file
        foreach ($this->folder->files as $file) {
            IndexFileJob::dispatch($file);
        }
    }
}
