<?php

namespace Amranidev\MicroBus\Sns\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static publish(string $event, $message)
 */
class PublisherFifo extends Facade
{
    /**
     * Get facade accessor.
     *
     * @return string
     */
    public static function getFacadeAccessor()
    {
        return 'sns.fifo.connection';
    }
}
