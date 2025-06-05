<?php

namespace App\Policies;

use App\Models\File;
use App\Models\IptcItem;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class IptcItemPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user, File $file): bool
    {
        // Assuming the user can view any IPTC items if they can view the associated file
        return $user->can('view', $file);
    }

    public function view(User $user, IptcItem $iptcItem): bool
    {
        // Assuming the user can view an IPTC item if they can view the associated file
        return $user->can('view', $iptcItem->file);
    }

    public function create(User $user, File $file): bool
    {
        // Assuming the user can create an IPTC item if they can update the associated file
        return $user->can('update', $file);
    }

    public function update(User $user, IptcItem $iptcItem): bool
    {
        // Assuming the user can update an IPTC item if they can update the associated file
        return $user->can('update', $iptcItem->file);
    }

    public function delete(User $user, IptcItem $iptcItem): bool
    {
        // Assuming the user can delete an IPTC item if they can update the associated file
        return $user->can('update', $iptcItem->file);
    }

    public function restore(User $user, IptcItem $iptcItem): bool
    {
        // Assuming the user can restore an IPTC item if they can update the associated file
        return $user->can('update', $iptcItem->file);
    }

    public function forceDelete(User $user, IptcItem $iptcItem): bool
    {
        // Assuming the user can force delete an IPTC item if they can update the associated file
        return $user->can('update', $iptcItem->file);
    }
}
