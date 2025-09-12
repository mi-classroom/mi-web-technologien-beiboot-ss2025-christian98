<?php

namespace App\Policies;

use App\Models\StorageConfig;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class StorageConfigPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->hasVerifiedEmail();
    }

    public function view(User $user, StorageConfig $storageConfig): bool
    {
        return $storageConfig->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        return $user->hasVerifiedEmail();
    }

    public function update(User $user, StorageConfig $storageConfig): bool
    {
        return $storageConfig->user_id === $user->id && $storageConfig->is_editable;
    }

    public function delete(User $user, StorageConfig $storageConfig): bool
    {
        return $storageConfig->user_id === $user->id && $storageConfig->is_editable;
    }

    public function restore(User $user, StorageConfig $storageConfig): bool
    {
        return $storageConfig->user_id === $user->id && $storageConfig->is_editable;
    }

    public function forceDelete(User $user, StorageConfig $storageConfig): bool
    {
        return $storageConfig->user_id === $user->id && $storageConfig->is_editable;
    }
}
