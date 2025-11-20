<?php

namespace App\Observers;

use App\Models\Task;
use App\Notifications\TaskUpdatedNotification;
use Illuminate\Support\Facades\Auth;

class TaskObserver
{
    public function updated(Task $task): void
    {
        // Si pas d'utilisateur assigné, on ne notifie pas
        if (!$task->responsable_id) {
            return;
        }

        // Ne pas notifier si c'est l'assigné lui-même qui modifie
        if (Auth::check() && Auth::id() === $task->responsable_id) { 
            return;
        }

        // Récupérer les changements
        $changes = [];
        $dirty = $task->getDirty();

        foreach ($dirty as $key => $newValue) {
            if (in_array($key, ['statut', 'priorite', 'echeance', 'nom', 'description'])) {
                $oldValue = $task->getOriginal($key);
                $changes[ucfirst($key)] = "{$oldValue} → {$newValue}";
            }
        }

        // Notifier l'assigné
        if (!empty($changes) && Auth::check()) {
            $task->assignee->notify(new TaskUpdatedNotification(
                $task,
                Auth::user(),
                $changes
            ));
        }
    }
}
