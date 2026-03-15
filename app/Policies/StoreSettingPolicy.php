<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\StoreSetting;
use Illuminate\Auth\Access\HandlesAuthorization;

class StoreSettingPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:StoreSetting');
    }

    public function view(AuthUser $authUser, StoreSetting $storeSetting): bool
    {
        return $authUser->can('View:StoreSetting');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:StoreSetting');
    }

    public function update(AuthUser $authUser, StoreSetting $storeSetting): bool
    {
        return $authUser->can('Update:StoreSetting');
    }

    public function delete(AuthUser $authUser, StoreSetting $storeSetting): bool
    {
        return $authUser->can('Delete:StoreSetting');
    }

    public function restore(AuthUser $authUser, StoreSetting $storeSetting): bool
    {
        return $authUser->can('Restore:StoreSetting');
    }

    public function forceDelete(AuthUser $authUser, StoreSetting $storeSetting): bool
    {
        return $authUser->can('ForceDelete:StoreSetting');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:StoreSetting');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:StoreSetting');
    }

    public function replicate(AuthUser $authUser, StoreSetting $storeSetting): bool
    {
        return $authUser->can('Replicate:StoreSetting');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:StoreSetting');
    }

}