<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use App\Models\Application;
use Carbon\Carbon;

class CheckApplicationsCommand extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'application:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check and update application statuses';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = Carbon::now()->toDateTimeString();
        $applications = Application::query()->whereDate('date', '<=', $now)
            ->where('status', 'Запланировано')->get();
        foreach ($applications as $application) {
            $application->status = 'Проведено';
            $application->update();
            $user = User::query()->where('id', $application->user_id)->first();
            $applications_user = Application::query()->where('id', $user->id)
                ->where('status', 'Проведено')->get();
            if (count($applications_user) % 5 === 0 && count($applications_user) >= 5) {
                if (count($applications_user) % 10 === 0) {
                    $user->discount = 20;
                } else {
                    $user->discount = 10;
                }
            } else {
                $user->discount = 0;
            }
            $user->update();
            $this->info("Запись №{$application->id} завершена.");
        }
        $this->info('Записи успешно проверены.');
    }
}
