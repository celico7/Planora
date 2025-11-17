<?php

namespace App\Notifications;

use App\Models\Task;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TaskUpdatedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Task $task,
        public User $updatedBy,
        public array $changes
    ) {}

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $changesList = collect($this->changes)
            ->map(fn($change, $field) => "- **{$field}** : {$change}")
            ->implode("\n");

        return (new MailMessage)
            ->subject("✏️ Tâche modifiée : {$this->task->nom}")
            ->greeting("Bonjour {$notifiable->name},")
            ->line("La tâche **{$this->task->nom}** a été modifiée par {$this->updatedBy->name}.")
            ->line("**Modifications :**")
            ->line($changesList)
            ->line("**Projet :** {$this->task->sprint->project->nom}")
            ->line("**Sprint :** {$this->task->sprint->nom}")
            ->action('Voir la tâche', url('/projects/' . $this->task->sprint->project_id . '/sprints/' . $this->task->sprint_id));
    }

    public function toArray(object $notifiable): array
    {
        return [
            'task_id' => $this->task->id,
            'task_nom' => $this->task->nom,
            'updated_by' => $this->updatedBy->name,
            'changes' => $this->changes,
            'message' => "La tâche '{$this->task->nom}' a été modifiée par {$this->updatedBy->name}",
            'project_nom' => $this->task->sprint->project->nom,
            'sprint_nom' => $this->task->sprint->nom,
        ];
    }
}
