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
    public $url;
    /**
     * Create a new notification instance.
     */
    public function __construct($mensajeCorto, $mensajeLargo, $url)
    {
        $this->mensajeCorto = $mensajeCorto;
        $this->mensajeLargo = $mensajeLargo;
        $this->url = $url;
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
                    ->subject($this->mensajeCorto)
                    ->view('emails.vencimiento', [
                        'usuario'      => $notifiable,
                        'mensajeCorto' => $this->mensajeCorto,
                        'mensajeLargo' => $this->mensajeLargo,
                        'logoPath'     => public_path('images/saico3.png'),
                        'url'          => $this->url,
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
