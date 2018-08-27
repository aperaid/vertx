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
      Commands\StartControllerServer::class,
      Commands\StartBroadcastServer::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
			$schedule->call(function () {
				
				$ch = curl_init();

				// set URL and other appropriate options
				curl_setopt($ch, CURLOPT_URL, "http://localhost/monitor/live/force/event");
				curl_setopt($ch, CURLOPT_HEADER, 0);

				// grab URL and pass it to the browser
				curl_exec($ch);

				// close cURL resource, and free up system resources
				curl_close($ch);
			})->everyThirtyMinutes();
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
