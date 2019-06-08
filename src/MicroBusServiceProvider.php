<?php

namespace Amranidev\MicroBus;

use Illuminate\Support\ServiceProvider;
use Amranidev\MicroBus\Sns\SnsServiceProvider;
use Amranidev\MicroBus\Sqs\SqsServiceProvider;

class MicroBusServiceProvider extends ServiceProvider
{
    /**
     * Boot the service provider.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(SqsServiceProvider::class);
        $this->app->register(SnsServiceProvider::class);
    }
}
