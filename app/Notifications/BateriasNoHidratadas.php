<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushMessage;
use NotificationChannels\WebPush\WebPushChannel;

class BateriasNoHidratadas extends Notification
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
            ->subject('Baterias con hidratacion pendiente')
            ->greeting('Lista de baterias con mas de 15 dias sin hidratacion:')
            ->line($this->body)
            ->action('Ver',$this->action)
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
            'modulo'    => 'baterias',
            'title'     => 'Baterias con hidratacion pendiente',
            'color'     => 'info',
            'route'     => $this->action,
            'body'      => $this->body,
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
