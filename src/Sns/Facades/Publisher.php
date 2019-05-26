<?php

namespace Amranidev\MicroBus\Sns\Facades;

use Illuminate\Support\Facades\Facade;

class Publisher extends Facade
{
    /**
     * Get facade accessor.
     *
     * @return string
     */
    public static function getFacadeAccessor()
    {
        return 'sns.connection';
    }
}
