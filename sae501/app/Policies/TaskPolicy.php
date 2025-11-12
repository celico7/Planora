<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TaskPolicy
{
    public function view(User $user, Task $task): bool
    {
        return $task->epic ? $user->can('view', $task->epic->sprint->project) : false;
    }

    public function update(User $user, Task $task): bool
    {
        return $task->epic ? $user->can('update', $task->epic->sprint->project) : false;
    }

    public function delete(User $user, Task $task): bool
    {
        return $task->epic ? $user->can('update', $task->epic->sprint->project) : false;
    }
}
