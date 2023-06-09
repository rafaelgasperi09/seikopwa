<?php

namespace App\Notifications;

use App\FormularioRegistro;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class NewTecnicalSupport extends Notification
{
    use Queueable;
    var $formularioRegistro;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(FormularioRegistro $formularioRegistro)
    {
        $this->formularioRegistro = $formularioRegistro;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail','database',WebPushChannel::class];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Nueva orden de soporte tecnico')
                    ->line('Se ha registrado una nueva orden de soporte tecnico')
                    ->line('Equipo :'.$this->formularioRegistro->equipo()->numero_parte)
                    ->line('Cliente :'.$this->formularioRegistro->cliente()->nombre)
                    ->action('VER', route('equipos.show_tecnical_support',array('id'=>$this->formularioRegistro->id,'tab'=>'soporte')));
                   
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'modulo'    => 'soporte_tecnico',
            'title'     => 'Ticket Asignado',
            'color'     => 'info',
            'route'     => route('equipos.detail',array('id'=>$this->formularioRegistro->equipo_id,'tab'=>'soporte')),
            'id'        => $this->formularioRegistro->id,
            'equipo'    => $this->formularioRegistro->equipo()->numero_parte,
            'equipo_id'    => $this->formularioRegistro->equipo()->id,
        ];
    }

    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage)
            ->title('Soporte Técnico')
            ->icon('/assets/img/mc.png')
            ->body('Se te ha asignado un nuevo ticket de soporte técnico.')
            ->action('Ver',route('equipos.detail',array('id'=>$this->formularioRegistro->equipo()->id,'show'=>'rows','tab'=>3)))
            ->data(['url' => route('equipos.detail',array('id'=>$this->formularioRegistro->equipo()->id,'show'=>'rows','tab'=>3))]);
    }
}
