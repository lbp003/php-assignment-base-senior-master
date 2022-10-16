<?php

namespace App\Notifications;

use App\Models\Customer;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NotifyCustomerOnRepairShopsAssigned extends Notification
{
    use Queueable;

    protected Customer $customer;
    protected array $repairShopsInArea;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(array $repairShopsInArea, Customer $customer)
    {
        $this->customer = $customer;
        $this->repairShopsInArea = $repairShopsInArea;
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
        return (new MailMessage)
                    ->subject('Fixico Damage Report')
                    ->greeting('Hi' . $this->customer->name)
                    ->line('Thank you for contacting us. Your report has been assigned to below repair shops')
                    ->line($this->repairShopsInArea)
                    ->line('Thank you for using our application!');
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
