<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ComentarioReporteNotification extends Notification
{
    use Queueable;

    public function __construct(
        public string $asunto,
        public string $mensaje,
        public string $url,
        public string $nombreDestinatario = 'usuario',
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject($this->asunto)
            ->view('emails.comentario-reporte', [
                'asunto' => $this->asunto,
                'mensaje' => $this->mensaje,
                'url' => $this->url,
                'nombreDestinatario' => $this->nombreDestinatario,
                'logoPath' => public_path('images/saico3.png'),
            ]);
    }
}
