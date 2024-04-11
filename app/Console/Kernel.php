<?php

namespace App\Console;

use App\Models\Post;
use App\User;
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

               /** @type User $admin */
               $admin = User::where('is_admin', '=', 1)->first();

               if(!$admin) {
                   throw new \LogicException(
                       "There is no admin present for post scheduling."
                       . "Please make sure your system has an admin account."
                   );
               }

               $post->title = "Zusammenfassung ($date)";
               $post->user_id = $admin->id;
               $post->body = 'Lorem Ipsum';
               $post->category_id = 1; // XXX: don't hardcode category values

               $post->save();
           } catch (\Exception $exception) {
               error_log($exception->getMessage());
               throw new \Exception(); // for the task to fail
           }
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
