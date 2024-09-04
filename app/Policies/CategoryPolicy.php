<?php

namespace App\Policies;

use App\Models\Category;
use App\Models\User;

class CategoryPolicy
{
    public function view(User $user, Category $category)
    {
        return $user->hasRole('lectura') || $user->hasRole('edicion') || $user->hasRole('administrador');
    }

    public function create(User $user)
    {
        return $user->hasRole('edicion') || $user->hasRole('administrador');
    }

    public function update(User $user, Category $category)
    {
        return $user->hasRole('edicion') || $user->hasRole('administrador');
    }

    public function delete(User $user, Category $category)
    {
        return $user->hasRole('edicion') || $user->hasRole('administrador');
    }
}
