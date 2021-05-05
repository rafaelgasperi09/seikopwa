<?php

namespace App\Notifications;

use App\FormularioRegistro;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewTecnicalSupportAssignTicket extends Notification
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
        return ['mail','database'];
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
            ->subject('Nuevo Ticket')
                    ->line('Se te ha asignado una nueva orden de soporte tecnico')
                    ->line('Equipo :'.$this->formularioRegistro->equipo()->numero_parte)
                    ->line('Cliente :'.$this->formularioRegistro->cliente()->nombre)
                    ->action('INICIAR', route('equipos.detail',array('id'=>$this->formularioRegistro->equipo_id,'tab'=>'soporte')))
                    ->line('NOTA : Asegurese de iniciar el proceso en el '.env('APP_NAME').' antes de proceder a trabajar con el equipo.');
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
}
