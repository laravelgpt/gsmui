<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentFailedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $paymentIntent;

    /**
     * Create a new notification instance.
     */
    public function __construct($paymentIntent)
    {
        $this->paymentIntent = $paymentIntent;
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
        $amount = ($this->paymentIntent->amount / 100);
        
        return (new MailMessage)
            ->subject('Payment Failed - GSM UI')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('We were unable to process your payment of $' . number_format($amount, 2) . ' USD.')
            ->line('Transaction ID: ' . $this->paymentIntent->id)
            ->line('Please update your payment method or try again.')
            ->action('Update Payment Method', url('/dashboard/billing'))
            ->line('If you need assistance, please contact our support team.');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'payment_failed',
            'transaction_id' => $this->paymentIntent->id,
            'amount' => $this->paymentIntent->amount / 100,
            'currency' => strtoupper($this->paymentIntent->currency),
            'message' => 'Payment failed',
        ];
    }
}
