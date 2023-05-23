<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushMessage;
use NotificationChannels\WebPush\WebPushChannel;

class DailyCheck extends Notification
{
    use Queueable;
    private $title;
    private $body;
    private $action;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($title='',$body='',$action='')
    {
        $this->title = $title;
        $this->body = $body;
        $this->action = $action;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail',WebPushChannel::class];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {   
        $var=explode('|',$this->body);
        $mail=new MailMessage;
        $mail ->subject($this->title)
        ->line("La siguiente lista no tiene chequeo diario");
        foreach($var as $v){
            $mail->line($v);
        }
        $mail->action('Equipos',route('dashboard'))
        ->line('Gracias por usar nuestra aplicación '.env('APP_NAME'));
        return ($mail);
       
           
                
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
            //
        ];
    }

    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage)
            ->title($this->title)
            ->icon('/assets/img/mc.png')
            ->body($this->body)
            ->action('Ver', $this->action);
    }
}
