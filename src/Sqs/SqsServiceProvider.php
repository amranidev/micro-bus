<?php

namespace Amranidev\MicroBus\Sqs;

use Illuminate\Config\Repository;
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
    }

    /**
     * Plug SQS Connector to the QueueManager.
     *
     * @return void
     */
    protected function addSqsConnector()
    {
        $this->app->afterResolving(QueueManager::class, function (QueueManager $manager) {
            /** @var \Illuminate\Config\Repository $config */
            $config = $this->app->make(Repository::class);

            $manager->addConnector('subscriber', function () use ($config) {
                $map = new JobMap($config->get('subscriber.subscribers'));
                return new SqsConnector($map);
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
            $configPath => config_path('subscriber.php'), ]);
    }
}
