<?php

namespace WendellAdriel\LaravelHut;

use Illuminate\Support\ServiceProvider;

class LaravelHutServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }

    /**
     * @return void
     */
    public function register()
    {
        //
    }
}
