<?php

namespace App\Policies;

use App\Models\Epic;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class EpicPolicy
{
    public function view(User $user, Epic $epic): bool
    {
        return $user->can('view', $epic->sprint->project);
    }

    public function update(User $user, Epic $epic): bool
    {
        return $user->can('update', $epic->sprint->project);
    }

    public function delete(User $user, Epic $epic): bool
    {
        return $user->can('update', $epic->sprint->project);
    }
}
