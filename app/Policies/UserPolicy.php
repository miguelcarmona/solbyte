<?php

namespace App\Policies;

use App\Models\User;
use App\Models\User as ModelUser;

class UserPolicy
{
    public function view(User $user, ModelUser $modelUser)
    {
        return $user->hasRole('administrador');
    }

    public function update(User $user, ModelUser $modelUser)
    {
        return $user->hasRole('administrador');
    }

    public function delete(User $user, ModelUser $modelUser)
    {
        return $user->hasRole('administrador');
    }
}
