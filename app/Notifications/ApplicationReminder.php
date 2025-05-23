<?php

namespace App\Notifications;

use App\Models\Application;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class ApplicationReminder extends Notification
{
    protected $application;

    public function __construct(Application $application)
    {
        $this->application = $application;
    }

    public function via($notifiable)
    {
        return [WebPushChannel::class];
    }

    public function toWebPush($notifiable)
    {
        $date = Carbon::parse($this->application->date);
        $time = Carbon::parse($this->application->time)->format('H:i');
        Carbon::setLocale('ru');
        $date_format = $date->translatedFormat('j F');

        return (new WebPushMessage())
            ->title('Напоминание о записи')
            ->body('У вас запись завтра, ' . $date_format . ' в ' . $time)
            ->action('Подтвердить', route('confirm', $this->application))
            ->icon('/icon.png');
    }
}
