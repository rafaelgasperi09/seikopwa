<?php

namespace App\Notifications;

use App\FormularioRegistro;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewMantenimientoPreventivo extends Notification
{
    use Queueable;
    protected $model;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(FormularioRegistro $model)
    {
        $this->model = $model;
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
            ->subject('Nuevo Mantenimiento ('.$this->model->formulario->nombre_menu.')')
            ->line('Se ha creado un nuevo formulario de mantenimiento preventivo.')
            ->line('Equipo :'.$this->model->equipo()->numero_parte)
            ->line('Cliente :'.$this->model->cliente()->nombre)
            ->action('Ver',route('equipos.detail',$this->model->equipo_id."?rows=show&tab=2"))
            ->line('Gracias por usar nuestra aplicación '.env('APP_NAME'));
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
            'modulo'    => 'mantenimiento_preventivo',
            'title'     => 'Nuevo Mantenimiento',
            'color'     => 'info',
            'route'     => route('equipos.detail',$this->model->equipo_id),
            'id'        => $this->model->id,
            'equipo'    => $this->model->equipo()->numero_parte,
            'equipo_id'    => $this->model->equipo_id,
            'cliente_id'    => $this->model->cliente_id,
        ];
    }
}
