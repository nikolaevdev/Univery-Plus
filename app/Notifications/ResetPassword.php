<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword as Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPassword extends Notification
{
    /**
     * Build the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Уведомление о сбросе пароля - '  . config('app.name'))
            ->line('Вы получаете это письмо, потому что мы получили запрос на сброс пароля для вашей учетной записи.')
            ->action('Сменить пароль', url(config('app.url').'/password/reset/'.$this->token).'?email='.urlencode($notifiable->email))
            ->line( 'Если вы не запрашивали сброс пароля, никаких дальнейших действий не требуется.');
    }
}
