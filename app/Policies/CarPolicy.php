<?php

namespace App\Policies;

use App\Models\Car;
use App\Models\User;

class CarPolicy
{
    public function view(User $user, Car $car)
    {
        return $user->hasRole('lectura') || $user->hasRole('edicion') || $user->hasRole('administrador');
    }

    public function create(User $user)
    {
        return $user->hasRole('edicion') || $user->hasRole('administrador');
    }

    public function update(User $user, Car $car)
    {
        return $user->hasRole('edicion') || $user->hasRole('administrador');
    }

    public function delete(User $user, Car $car)
    {
        return $user->hasRole('edicion') || $user->hasRole('administrador');
    }
}
