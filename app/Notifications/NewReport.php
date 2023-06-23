<?php

namespace App\Notifications;

use App\FormularioRegistro;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewReport extends Notification
{
    use Queueable;
    protected $model;
    protected $user;
    protected $ruta;
    protected $notificados;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(FormularioRegistro $model,$user,$notificados=array())
    {
        $this->model = $model;
        $this->user = $user;
        $this->ruta = '';
        $this->notificados = $notificados;
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
        $subject='Nuevo Reporte. ';
        $tipo='';
        switch($this->model->formulario->tipo){
            case 'daily_check': 
                $tipo='DailyCheck ';
                $this->ruta=route('equipos.show_daily_check',$this->model->id);
                break;
            case 'mant_prev': 
                $tipo='Mantenimiento Preventivo ';
                $this->ruta=route('equipos.show_mant_prev',$this->model->id);
                break;
            case 'serv_tec': 
                $tipo='Servicio Técnico ';
                $this->ruta=route('equipos.show_tecnical_support',$this->model->id);
                break;
        }
        $subject='Nuevo '.$tipo;
        if(env('APP_ENV')=='local'){
            $subject.='('.$this->user->full_name.')';
        }
        $mas_info='Notificados:'.PHP_EOL;
        if($this->user->id==1){
            foreach($this->notificados as $n){
                $mas_info.=$n->email.','.PHP_EOL;
            }
        }
        return (new MailMessage)
            ->subject($subject)
                    ->line("Se ha creado un nuevo formulario de $tipo  que requiere su firma.")
                    ->line('Equipo :'.$this->model->equipo()->numero_parte)
                    ->line('Usuario :'.$this->model->creador->full_name)
                    ->action('Ver',$this->ruta)
                    ->line($mas_info)
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
            'route'     => $this->ruta,
            'id'        => $this->model->id,
            'equipo'    => $this->model->equipo()->numero_parte,
            'equipo_id'    => $this->model->equipo()->id,
        ];
    }
}
