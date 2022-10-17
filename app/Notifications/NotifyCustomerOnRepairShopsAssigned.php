<?php

namespace App\Notifications;

use App\Models\Customer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;

class NotifyCustomerOnRepairShopsAssigned extends Notification implements ShouldQueue
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
        $mail = (new MailMessage)
                    ->subject('Fixico Damage Report')
                    ->greeting('Hi ' . $this->customer->name . ',');
        if (!empty($this->repairShopsInArea)) {
            $mail->line(
                new HtmlString(
                    '<b>We are happy to inform you. Your damage report has been assigned to below repair shops</b>'
                )
            );

            foreach ($this->repairShopsInArea as $repairShop) {
                $mail->line('Repair Shop: ' . $repairShop['name'])
                    ->line('Latitude: ' . $repairShop['latitude'])
                    ->line('Longitude: ' . $repairShop['longitude'])
                    ->line(new HtmlString('<hr>'));
            }
        } else {
            $mail->line(new HtmlString('<br>'))
                ->line(new HtmlString('<b>Sorry. No repair shops available close to your area.</b>'));
        }

        return $mail->line('Thank you for using Fixico!')
            ->line(new HtmlString('<br>'))
            ->salutation(new HtmlString('<b>Fixico</b>'));
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
