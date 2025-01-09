<?php

// namespace App\Console;

// use Illuminate\Console\Scheduling\Schedule;
// use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

// class Kernel extends ConsoleKernel
// {
//     /**
//      * Define the application's command schedule.
//      */
//     protected function schedule(Schedule $schedule): void
//     {
//         // $schedule->command('inspire')->hourly();
//     }
//     public function pushMiddleware($middleware)
//     {
//         if (array_search($middleware, $this->middleware) === false) {
//             $this->middleware[] = $middleware;
//         }

//         return $this;
//     }
//     /**
//      * Register the commands for the application.
//      */
//     protected $routeMiddleware = [
//         'rolesMiddleware' => \App\Http\Middleware\rolesMiddleware::class
//     ];

//     protected function commands(): void
//     {
//         $this->load(__DIR__ . '/Commands');

//         require base_path('routes/console.php');
//     }
// }
