<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;

class NotificacionesEyC extends Notification
{
    use Queueable;

    private $certificado;
    private $diasRestantes;

    /**
     * Create a new notification instance.
     * 
     * @return void
     */
    public function __construct($certificado, $diasRestantes)
    {
        //
        $this->certificado = $certificado;
        $this->diasRestantes = $diasRestantes;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        //return ['mail'];
        return ['database'];  // Puedes añadir 'mail' si deseas enviar también por correo
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        /*Log::info('Creating notification with data:', [
            'certificado_id' => $this->certificado->id,
            'diasRestantes' => $this->diasRestantes,
        ]);*/
        return [
            //
            'mensaje' => "La calibración para el certificado '{$this->certificado->No_certificado}' está programada para dentro de {$this->diasRestantes} días.",
            'certificado_id' => $this->certificado->idCertificados,
            'prox_fecha_calibracion' => $this->certificado->prox_fecha_calibracion,
        ];
    }
}
