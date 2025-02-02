<?php

namespace App\Policies;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class OwnershipPolicy
{
    public function before(?User $user, $ability)
    {
        if ($user && $user->isAdmin()) {
            return true;
        }
    }

    protected function isOwner(?User $user, Model $model)
    {
        return $user && method_exists($model, 'user') && $model->user->is($user);
    }

    public function hasPermission(?User $user, Model $model)
    {
        return $this->isOwner($user, $model);
    }
}
