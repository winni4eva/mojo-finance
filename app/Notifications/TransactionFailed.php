<?php

namespace App\Notifications;

use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TransactionFailed extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(public Account $account, public Account $creditAccount, public int $userId, public int $amount)
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
                    ->action('TRANSACTION FAILED', url('/'))
                    ->subject("Transaction Failed")
                    ->greeting("Hi {$this->account->user->first_name}")
                    ->line("A transfer of USD {$this->amount} to account (000{$this->creditAccount->id}) owned by {$this->creditAccount->user->first_name} failed")
                    //->line("Account balance is USD ({$this->transaction->debitAccount->amount})")
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
