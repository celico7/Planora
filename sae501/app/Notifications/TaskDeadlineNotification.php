<?php

namespace App\Notifications;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TaskDeadlineNotification extends Notification
{
    use Queueable;

    public function __construct(public Task $task, public string $type = 'proche')
    {
        // $type: 'proche' | 'retard'
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $subject = $this->type === 'retard'
            ? "⚠️ Tâche en retard : {$this->task->nom}"
            : "🔔 Échéance proche : {$this->task->nom}";

        $message = (new MailMessage)
            ->subject($subject)
            ->greeting("Bonjour {$notifiable->name},")
            ->line($this->type === 'retard'
                ? "La tâche **{$this->task->nom}** est en retard !"
                : "La tâche **{$this->task->nom}** arrive bientôt à échéance.")
            ->line("**Projet :** {$this->task->sprint->project->nom}")
            ->line("**Sprint :** {$this->task->sprint->nom}")
            ->line("**Échéance :** " . $this->task->echeance->format('d/m/Y'));

        if ($this->type === 'retard') {
            $message->error();
        }

        return $message->action('Voir la tâche', url('/projects/' . $this->task->sprint->project_id . '/sprints/' . $this->task->sprint_id));
    }

    public function toArray(object $notifiable): array
    {
        return [
            'task_id' => $this->task->id,
            'task_nom' => $this->task->nom,
            'type' => $this->type,
            'message' => $this->type === 'retard'
                ? "La tâche '{$this->task->nom}' est en retard !"
                : "La tâche '{$this->task->nom}' arrive à échéance bientôt.",
            'project_nom' => $this->task->sprint->project->nom,
            'sprint_nom' => $this->task->sprint->nom,
            'echeance' => $this->task->echeance->format('d/m/Y'),
        ];
    }
}
