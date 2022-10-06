<?php

namespace WendellAdriel\LaravelHut\Facades;

use Illuminate\Support\Facades\Facade;

class LaravelHut extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'laravel-hut';
    }
}
