<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\ChannelSale;
use Illuminate\Auth\Access\HandlesAuthorization;

class ChannelSalePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:ChannelSale');
    }

    public function view(AuthUser $authUser, ChannelSale $channelSale): bool
    {
        return $authUser->can('View:ChannelSale');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:ChannelSale');
    }

    public function update(AuthUser $authUser, ChannelSale $channelSale): bool
    {
        return $authUser->can('Update:ChannelSale');
    }

    public function delete(AuthUser $authUser, ChannelSale $channelSale): bool
    {
        return $authUser->can('Delete:ChannelSale');
    }

    public function restore(AuthUser $authUser, ChannelSale $channelSale): bool
    {
        return $authUser->can('Restore:ChannelSale');
    }

    public function forceDelete(AuthUser $authUser, ChannelSale $channelSale): bool
    {
        return $authUser->can('ForceDelete:ChannelSale');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:ChannelSale');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:ChannelSale');
    }

    public function replicate(AuthUser $authUser, ChannelSale $channelSale): bool
    {
        return $authUser->can('Replicate:ChannelSale');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:ChannelSale');
    }

}