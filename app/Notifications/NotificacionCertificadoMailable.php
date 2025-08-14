<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NotificacionCertificadoMailable extends Notification
{
    use Queueable;

    public $mensajeCorto;
    public $mensajeLargo;
    /**
     * Create a new notification instance.
     */
    public function __construct($mensajeCorto, $mensajeLargo)
    {
        $this->mensajeCorto = $mensajeCorto;
        $this->mensajeLargo = $mensajeLargo;
    }
    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    /*->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');*/
                    /*+++++ */
                    /*->subject($this->mensajeCorto)
                    ->line($this->mensajeLargo)
                    ->line('Este es un aviso automático. No responder a este correo.');*/
                    ->subject($this->mensajeCorto)
                    ->view('emails.vencimiento', [
                        'usuario'      => $notifiable,
                        'mensajeCorto' => $this->mensajeCorto,
                        'mensajeLargo' => $this->mensajeLargo,
                    ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
