<?php

namespace App\Policies;

use App\Models\Folder;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class FolderPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool {}

    public function view(User $user, Folder $folder): bool {}

    public function create(User $user): bool {}

    public function update(User $user, Folder $folder): bool {}

    public function delete(User $user, Folder $folder): bool {}

    public function restore(User $user, Folder $folder): bool {}

    public function forceDelete(User $user, Folder $folder): bool {}
}
