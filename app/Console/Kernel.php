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
        Commands\DemoCron::class,
        // Commands\BillHalfMonthCOC1::class
        // Commands\BillHalfMonthCOC2::class
        // Commands\MonthlyUpdateAdmin1::class
        // Commands\MonthlyUpdateAdmin2::class
        Commands\PromoCodeExpiry::class,
        Commands\ZipUpdate::class,
        // Commands\BillingApprovalNotify::class,
        Commands\InsuffCOCNotify::class,
        Commands\PurgeNotify::class,
        Commands\ExcelExportNotify::class,
        Commands\DailyReportNotify::class,
        Commands\MasterTrackerNotify::class,
        Commands\DailyNotificationControl::class,
        Commands\ReportShareNotify::class,
        Commands\S3FileUpdate::class,
        Commands\ProgressExport::class,
        Commands\InsuffCustomNotify::class,
        Commands\SendLinkSmsSetting::class

    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();

        $schedule->command('demo:cron')
        ->everyMinute();

        // $schedule->command('billhalfmonthcoc1:update')
        // ->monthlyOn(15,'08:00');

        // $schedule->command('billhalfmonthcoc2:update')
        // ->monthlyOn(date('t'),'08:00');

        // $schedule->command('billmonthlycoc:update')
        // ->monthlyOn(date('t'),'08:00');

        // $schedule->command('monthlyadmin1:update')
        // ->monthlyOn(15,'08:00');

        // $schedule->command('monthlyadmin2:update')
        // ->monthlyOn(date('t'),'08:00');

        $schedule->command('zip:update')
        ->dailyAt('00:00');

        $schedule->command('promocode:expiry')
        ->hourly();

        // $schedule->command('billingApproval:notify')
        // ->dailyAt('00:00');

        $schedule->command('insuffCoc:notify')
        ->dailyAt('00:00');

        // $schedule->command('purge:notify')
        // ->dailyAt('00:00');

        // $schedule->command('excelExport:notify')
        // ->weeklyOn(1,'00:00');

        $schedule->command('dailyReport:notify')
        ->dailyAt('06:00');

        $schedule->command('masterTracker:notify')
        ->dailyAt('06:00');

        
        $schedule->command('dailynotification:control')
        ->dailyAt('11:00');

        $schedule->command('reportShare:notify')
        ->cron('0 6 * * 1-6');

        $schedule->command('s3fileupload:update')
        ->cron('0 */2 * * *');

        $schedule->command('progress-export:notify')
        ->everyTenMinutes();

        $schedule->command('insuffcustom:notify')
        ->dailyAt('11:00');

        $schedule->command('sendlink:notify')
        ->dailyAt('11:00');
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
