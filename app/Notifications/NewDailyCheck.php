<?php

namespace App\Notifications;

use App\FormularioRegistro;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewDailyCheck extends Notification
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
        return ['mail'/*,'database'*/];
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
            ->subject('Nuevo DailyCheck')
                    ->line('Se ha creado un nuevo formulario daily check que requiere su firma.')
                    ->line('Equipo :'.$this->model->equipo()->numero_parte)
                    ->action('Firmar',route('equipos.edit_daily_check',$this->model->id))
                    ->line('Gracias por usar nuestra aplicacion GMPCheck');
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
            'modulo'    => 'Daily Check',
            'title'     => 'Firma pendiente',
            'color'     => 'info',
            'route'     => route('equipos.edit_daily_check',$this->model->id),
            'id'        => $this->model->id,
            'equipo'    => $this->model->equipo()->numero_parte,
            'equipo_id'    => $this->model->equipo()->id,
        ];
    }
}
