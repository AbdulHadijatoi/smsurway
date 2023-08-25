<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{

    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\cronSms::class,
        Commands\SendSMS::class,
    ];
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('compaign:sms')->everyFiveMinutes(); 
        $schedule->command('send:sms')->everyMinute()->runInBackground();
        $schedule->command('schedule:sms')->everyMinute()->withoutOverlapping()->runInBackground();
        // $schedule->command('schedule:sms')->everyMinute()->withoutOverlapping()->runInBackground();
        $schedule->command('queue:work --daemon')->everyFiveMinutes()->withoutOverlapping();
        $schedule->command('report:sms')->everyMinute()->runInBackground()->withoutOverlapping();
        $schedule->command('command:daily-reset')->daily();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
