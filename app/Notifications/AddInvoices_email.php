<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AddInvoices_email extends Notification
{
    use Queueable;
    private $invoices_id;
    /**
     * Create a new notification instance.
     */
    public function __construct($invoices_id)
    {
        $this->invoices_id=$invoices_id;
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
        return (new MailMessage)
                    ->subject('اضافة فاتورة جديدة')
                    ->line('اضافة فاتورة جديدة  ')
                    ->action('عرض الفاتورة', url('http://localhost/invoices/public/invoices/invoice_detiles/'.$this->invoices_id))
                    ->line('شكرا لاستخدامك مورا سوفا لادارة الفواتير');
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
