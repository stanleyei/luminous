<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PasswordReset extends Notification
{
    use Queueable;

    protected $token;

    /**
     * Create a new notification instance.
     */
    public function __construct($token)
    {
        $this->token = $token;
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
        $time = config('auth.passwords.' . config('auth.defaults.passwords') . '.expire');
        return (new MailMessage())
                    ->subject('重置密碼通知')
                    ->line('您收到這封電子郵件是因為我們收到了您帳戶的密碼重置請求。')
                    ->action('重置密碼', route('password.reset', ['token' => $this->token, 'email' => $notifiable->getEmailForPasswordReset()]))
                    ->line("此密碼重置連結將在 {$time} 分鐘後過期。")
                    ->line('如果您未請求重置密碼，則無需進一步操作。');
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
