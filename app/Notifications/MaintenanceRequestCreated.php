<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MaintenanceRequestCreated extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        private $maintenanceRequest
    ) {}

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Nueva Solicitud de Mantenimiento - #'.$this->maintenanceRequest->id)
            ->greeting('Estimado/a '.$notifiable->name)
            ->line('Se ha creado una nueva solicitud de mantenimiento.')
            ->line('**Título:** '.$this->maintenanceRequest->title)
            ->line('**Prioridad:** '.$this->getPriorityLabel($this->maintenanceRequest->priority))
            ->line('**Categoría:** '.$this->maintenanceRequest->maintenance_category->name)
            ->line('**Solicitado por:** '.$this->maintenanceRequest->requested_by->name)
            ->action('Ver Solicitud', route('maintenance-requests.show', $this->maintenanceRequest->id))
            ->line('Por favor, revise y procese esta solicitud lo antes posible.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'maintenance_request_created',
            'maintenance_request_id' => $this->maintenanceRequest->id,
            'title' => $this->maintenanceRequest->title,
            'priority' => $this->maintenanceRequest->priority,
            'category' => $this->maintenanceRequest->maintenance_category->name,
            'requested_by' => $this->maintenanceRequest->requested_by->name,
            'created_at' => $this->maintenanceRequest->created_at,
            'message' => 'Nueva solicitud de mantenimiento: '.$this->maintenanceRequest->title,
            'action_url' => route('maintenance-requests.show', $this->maintenanceRequest->id),
        ];
    }

    private function getPriorityLabel(string $priority): string
    {
        return match ($priority) {
            'low' => 'Baja',
            'medium' => 'Media',
            'high' => 'Alta',
            'critical' => 'Crítica',
            default => $priority,
        };
    }
}
