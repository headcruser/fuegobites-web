<?php

namespace App\Notifications;

use App\Models\Cotizacion;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EnviarCotizacionCliente extends Notification
{
    use Queueable;

    /**
     * Cotizacion a enviar
     *
     * @var Cotizacion
     */
    protected $cotizacion;

    /**
     * Lista de contactos a enviar como copia
     *
     * @var array
     */
    protected $contactos;

    /**
     * Lista de documentos a adjuntar en donde se especifica el nombre y el contenido del archivo
     *
     * @var array
     */
    protected $archivos;

    /**
     * Create a new notification instance.
     */
    public function __construct(Cotizacion $cotizacion, array $contactos, array $archivos)
    {
        $this->cotizacion = $cotizacion;
        $this->contactos = $contactos;
        $this->archivos = $archivos;
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
        $mail = (new MailMessage);

        foreach ($this->contactos as $email) {
            $mail->cc($email);
        }

        $mail->subject('Cotización ' . $this->cotizacion->folio . ' ' . $this->cotizacion->nombre)
            ->markdown('vendor.mail.html.cotizaciones.enviar_cotizacion', [
                'cotizacion'    => $this->cotizacion,
                'cliente'       => $notifiable,
                'vendedor'      => $notifiable
            ]);

        foreach ($this->archivos as $extension => $archivo) {
            $mail->attachData($archivo, $extension);
        }

        return $mail;
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
