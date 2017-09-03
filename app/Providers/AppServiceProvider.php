<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot() {
        View::share('tabs', [
            [ 'href'=>'/', 'display'=>'Home' ],
            [ 'href'=>'/reps', 'display'=>'Representatives' ],
            // [ 'href'=>'/words', 'display'=>'Words' ],
            [ 'href'=>'/elections', 'display'=>'Elections' ],
            [ 'href'=>'/floorupdates', 'display'=>'Floor Updates' ],
            [ 'href'=>'/statebills', 'display'=>'State Bills' ],
            [ 'href'=>'/statetax', 'display'=>'State Taxes' ],
            // [ 'href'=>'/regulations', 'display'=>'Regulations' ]
        ]);
        View::share('google_analytics_key', config('services.analytics.google.key'));
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
