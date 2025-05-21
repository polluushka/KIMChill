<?php

namespace App\Console\Commands;

use App\Models\Application;
use App\Notifications\ApplicationReminder;
use Carbon\Carbon;
use Illuminate\Console\Command;

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
        $target = Carbon::now()->addDay()->startOfMinute();

        $applications = Application::whereDate('date', $target->toDateString())
            ->whereTime('scheduled_at', $target->toTimeString())
            ->get();

        foreach ($applications as $application) {
            $application->user->notify(new ApplicationReminder($application));
        }

        $this->info('Напоминания отправлены.');
    }
}
