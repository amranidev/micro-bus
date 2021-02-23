<?php

namespace Amranidev\MicroBus\Sqs;

use Illuminate\Queue\QueueManager;
use Illuminate\Support\ServiceProvider;
use Amranidev\MicroBus\Sqs\Console\JobSubscriberCommand;

class SqsServiceProvider extends ServiceProvider
{
    /**
     * Boot the ServiceProvider.
     *
     * @return void
     */
    public function boot()
    {
        $this->commands([JobSubscriberCommand::class]);

        SqsJob::macro('getInstance', function () {
            return $this->instance;
        });

        $this->publishConfiguration();
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->addSqsConnector();
        $this->bindJobMab();
    }

    /**
     * Plug SQS Connector to the QueueManager.
     *
     * @return void
     */
    protected function addSqsConnector()
    {
        $this->app->afterResolving(QueueManager::class, function (QueueManager $manager) {
            $manager->addConnector('subscriber', function () {
                return new SqsConnector();
            });
        });

        $this->app->afterResolving(QueueManager::class, function (QueueManager $manager) {
            $manager->addConnector('subscriber-fifo', function () {
                return new SqsFifoConnector();
            });
        });
    }

    /**
     * Publish publisher configuration file.
     *
     * @return void
     */
    protected function publishConfiguration()
    {
        $configPath = __DIR__.'/../../config/subscriber.php';
        $this->publishes([
            $configPath => base_path('config/subscriber.php'), ], 'subscriber');
    }

    /**
     * Bind JobMap.
     *
     * @return void
     */
    protected function bindJobMab()
    {
        $this->app->singleton('jobmap', function ($app) {
            $map = config('subscriber.subscribers');

            return new JobMap($map);
        });
    }
}
