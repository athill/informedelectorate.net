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
            [ 'href'=>'/words', 'display'=>'Words' ],
            [ 'href'=>'/floorupdates', 'display'=>'Floor Updates' ],
            [ 'href'=>'statebills', 'display'=>'State Bills' ],
            [ 'href'=>'statetax', 'display'=>'State Taxes' ]
        ]);
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
