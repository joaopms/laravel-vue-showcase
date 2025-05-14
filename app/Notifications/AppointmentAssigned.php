<?php

namespace App\Notifications;

use App\Models\Appointment;
use App\TimeOfDay;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AppointmentAssigned extends Notification
{
    use Queueable;

    public function __construct(private readonly Appointment $appointment) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $appName = config('app.name');
        $preferredTime = $this->appointment->preferred_time;

        return (new MailMessage)
            ->subject("Your visit to $appName")
            ->line("We're glad to inform you we're expecting your visit on {$this->appointment->preferred_date_formatted} ({$this->appointment->preferred_date->longRelativeToNowDiffForHumans()})!")
            ->line($preferredTime === TimeOfDay::AllDay
                ? 'Come whenever is best for you!'
                : "If possible, please come in the {$preferredTime->value}.")
            ->line('Thank you for trusting us :)');
    }
}
