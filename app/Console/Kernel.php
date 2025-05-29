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
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // Clear expired cache daily
        $schedule->command('cache:prune-stale-tags')->daily();
        
        // Run database backups daily
        $schedule->command('backup:run')->daily()->at('01:00');
        
        // Process affiliate payouts weekly
        $schedule->command('affiliates:process-payouts')->weekly()->mondays()->at('03:00');
        
        // Refresh analytics data hourly
        $schedule->command('analytics:refresh')->hourly();
        
        // Send inactivity alerts daily
        $schedule->command('users:inactivity-alerts')->daily()->at('09:00');
        
        // Clean old logs weekly
        $schedule->command('log:clean')->weekly()->at('02:00');
        
        // Update sitemap weekly
        $schedule->command('sitemap:generate')->weekly()->sundays()->at('04:00');
        
        // Check for expired licenses daily
        $schedule->command('licenses:check-expiration')->daily()->at('00:00');
        
        // Send weekly reports to admins
        $schedule->command('reports:send-weekly')->weekly()->fridays()->at('08:00');
        
        // Optimize database weekly
        $schedule->command('db:optimize')->weekly()->saturdays()->at('02:30');
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
