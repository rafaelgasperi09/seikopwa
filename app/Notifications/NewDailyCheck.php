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
    public function __construct(FormularioRegistro $model,$user_name)
    {
        $this->model = $model;
        $this->user_name = $user_name;
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
      
        $subject='Nuevo DailyCheck. ';
        if(env('APP_ENV')=='local'){
            $subject.='('.$this->user_name.')';
        }
        return (new MailMessage)
            ->subject($subject)
                    ->line('Se ha creado un nuevo formulario daily check que requiere su firma.')
                    ->line('Equipo :'.$this->model->equipo()->numero_parte)
                    ->line('Usuario :'.$this->model->creador->full_name)
                    ->action('Firmar',route('equipos.edit_daily_check',$this->model->id))
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
            'modulo'    => 'daily_check',
            'title'     => 'Firma pendiente',
            'color'     => 'info',
            'route'     => route('equipos.edit_daily_check',$this->model->id),
            'id'        => $this->model->id,
            'equipo'    => $this->model->equipo()->numero_parte,
            'equipo_id'    => $this->model->equipo()->id,
        ];
    }
}
