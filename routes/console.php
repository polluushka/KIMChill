<?php

use App\Console\Commands\SendApplicationReminders;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Console\Commands\CheckApplicationsCommand;
use Illuminate\Support\Facades\Schedule;

Schedule::command(CheckApplicationsCommand::class)->everyMinute();
Schedule::command(SendApplicationReminders::class)->everyMinute();


Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');


