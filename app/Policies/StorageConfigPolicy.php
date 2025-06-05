<?php

namespace App\Policies;

use App\Models\StorageConfig;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class StorageConfigPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool {}

    public function view(User $user, StorageConfig $storageConfig): bool {}

    public function create(User $user): bool {}

    public function update(User $user, StorageConfig $storageConfig): bool {}

    public function delete(User $user, StorageConfig $storageConfig): bool {}

    public function restore(User $user, StorageConfig $storageConfig): bool {}

    public function forceDelete(User $user, StorageConfig $storageConfig): bool {}
}
