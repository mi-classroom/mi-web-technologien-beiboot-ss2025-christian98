<?php

namespace App\Listeners;

use App\Events\UserCreated;
use App\Jobs\IndexStorageJob;
use App\Services\Storage\Provider\Local\LocalProvider;
use Illuminate\Support\Facades\Bus;

class CreateLocalStorageConfigForNewUser
{
    /**
     * Handle the event.
     */
    public function handle(UserCreated $event): void
    {
        $storageConfig = $event->user->storageConfigs()->create([
            'name' => 'Local Storage',
            'provider_type' => 'local',
            'provider_options' => [],
            'status' => 'connected',
            'is_editable' => false,
        ]);

        // Create the storage directory for the user
        /** @var LocalProvider $storageProvider */
        $storageProvider = $storageConfig->getStorage();
        $storageProvider->getStorage()->makeDirectory('/');

        // Index the storage configuration
        Bus::dispatch(new IndexStorageJob($storageConfig));
    }
}
