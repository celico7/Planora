<?php

namespace App\Console\Commands;

use App\Models\Task;
use App\Notifications\TaskDeadlineNotification;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CheckTaskDeadlines extends Command
{
    protected $signature = 'tasks:check-deadlines';
    protected $description = 'Vérifie les échéances des tâches et envoie des notifications';

    public function handle(): int
    {
        $now = Carbon::now();
        $in2Days = $now->copy()->addDays(2);

        // Tâches en retard (échéance dépassée, statut ≠ terminé)
        $lateTasks = Task::where('echeance', '<', $now)
            ->where('statut', '!=', 'terminé')
            ->whereNotNull('responsable_id') // responsable_id
            ->with(['assignee', 'sprint.project'])
            ->get();

        foreach ($lateTasks as $task) {
            $task->assignee->notify(new TaskDeadlineNotification($task, 'retard'));
            $this->info("Notification retard envoyée pour : {$task->nom}");
        }

        // Tâches avec échéance proche (dans les 2 jours)
        $upcomingTasks = Task::whereBetween('echeance', [$now, $in2Days])
            ->where('statut', '!=', 'terminé')
            ->whereNotNull('responsable_id') 
            ->with(['assignee', 'sprint.project'])
            ->get();

        foreach ($upcomingTasks as $task) {
            // Vérifier si on a pas déjà notifié aujourd'hui
            $alreadyNotified = $task->assignee->notifications()
                ->where('type', TaskDeadlineNotification::class)
                ->where('data->task_id', $task->id)
                ->where('data->type', 'proche')
                ->whereDate('created_at', $now)
                ->exists();

            if (!$alreadyNotified) {
                $task->assignee->notify(new TaskDeadlineNotification($task, 'proche'));
                $this->info("Notification échéance proche envoyée pour : {$task->nom}");
            }
        }

        $this->info('Vérification des échéances terminée.');
        return Command::SUCCESS;
    }
}
