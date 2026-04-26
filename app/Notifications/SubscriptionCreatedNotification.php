
<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SubscriptionCreatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $subscription;

    /**
     * Create a new notification instance.
     */
    public function __construct($subscription)
    {
        $this->subscription = $subscription;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Subscription Activated - GSM UI')
            ->greeting('Welcome ' . $notifiable->name . '!')
            ->line('Your Pro subscription has been successfully activated!')
            ->line('You now have unlimited access to all premium components and templates.')
            ->action('Explore Premium Content', url('/components'))
            ->line('Thank you for choosing GSM UI!');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'subscription_created',
            'subscription_id' => $this->subscription->id,
            'status' => $this->subscription->status,
            'message' => 'Subscription activated',
        ];
    }
}
