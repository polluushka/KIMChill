<?php

namespace App\Console\Commands;

use App\Models\Application;
use App\Notifications\ApplicationCreated;
use App\Notifications\ApplicationReminder;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

class SendApplicationReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'appointments:remind';
    protected $description = 'Отправка напоминаний за день до записи';

    public function handle()
    {
        $tomorrow = Carbon::tomorrow();
        $applications = Application::whereDate('date', $tomorrow->toDateString())->get();

        foreach ($applications as $application) {
            Notification::send($application->user_id, new ApplicationReminder($application));
        }

        $this->info('Напоминания отправлены.');
    }
}
