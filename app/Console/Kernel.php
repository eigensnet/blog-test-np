<?php

namespace App\Console;

use App\Models\Post;
use DateTime;
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
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
       $schedule->call(function () {
           try {
               $date = (new DateTime)->format('m.Y');
               $post = new Post;

               $post->title = "Zusammenfassung ($date)";
               $post->user_id = 1; // XXX: don't hardcode values
               $post->body = '';
               $post->category_id = 1; // XXX: don't hardcode values

               $post->save();
           } catch (\Exception $exception) {
               error_log($exception->getMessage());
               throw new \Exception(); // for the task to fail
           }
           return 0;
       })->monthlyOn(1, '06:00');
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
