<?php

namespace App\Providers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;
use App\Channel;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // \View::share('channels', Channel::all());

        View::composer('*', function ($view) {
            $channels = Cache::remember('channels', 60, function() {
                return Channel::all();
            });
            $view->with('channels', $channels);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

    }
}
