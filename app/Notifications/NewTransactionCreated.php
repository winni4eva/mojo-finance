<?php

namespace App\Notifications;

use App\Models\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewTransactionCreated extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(public Transaction $transaction)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $appName = config('app.name');

        return (new MailMessage)
                    ->line("{$appName}")
                    ->action('TRANSACTION', url('/'))
                    ->subject(' New Transaction Created')
                    ->greeting("Hi {$this->transaction->user->first_name}")
                    ->line("A transfer of USD {$this->transaction->amount} was sent from account (000{$this->transaction->creditAccount->id}) to account (000{$this->transaction->debitAccount->id})")
                    ->line("Account balance is USD ({$this->transaction->debitAccount->amount})")
                    ->line('kind Regards.');
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
}
