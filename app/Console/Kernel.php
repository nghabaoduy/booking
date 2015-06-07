<?php namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Davibennun\LaravelPushNotification\Facades\PushNotification;

class Kernel extends ConsoleKernel {

	/**
	 * The Artisan commands provided by your application.
	 *
	 * @var array
	 */
	protected $commands = [
		'App\Console\Commands\Inspire',
	];

	/**
	 * Define the application's command schedule.
	 *
	 * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
	 * @return void
	 */
	protected function schedule(Schedule $schedule)
	{
		$schedule->command('inspire')
				 ->hourly();

        $schedule->call(function(){
            //..
            PushNotification::app('appNameIOS')
            ->to('51518ed1a9dfb59fdc058375a8d249311a3e4e8357bf010ce47688eb1399c3b0')
                ->send('Hello World, i`m a push message');
        })->everyFiveMinutes();
	}

}
