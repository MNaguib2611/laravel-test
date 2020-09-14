<?php

namespace App\Providers;

use App\Services\TextModerator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->when(TextModerator::class)->needs('$baseUrl')->give('https://api.sightengine.com/1.0/text/check.json');
        $this->app->when(TextModerator::class)->needs('$apiUser')->give(config('services.sightengine.api_user'));
        $this->app->when(TextModerator::class)->needs('$apiSecret')->give(config('services.sightengine.api_secret'));
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
