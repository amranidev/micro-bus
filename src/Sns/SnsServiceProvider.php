<?php

namespace Amranidev\MicroBus\Sns;

use Carbon\Laravel\ServiceProvider;

class SnsServiceProvider extends ServiceProvider
{
    /**
     * Boot the ServiceProvider.
     *
     * @return void
     */
    public function boot()
    {
       $this->publishConfiguration();
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerSnsManager();
        $this->registerSnsConnector();
        $this->registerSnsConnection();
    }

    /**
     * Register SnsManager.
     *
     * @retun void
     */
    protected function registerSnsManager()
    {
        $this->app->singleton('sns', function ($app) {
            return new SnsManager($app);
        });
    }

    /**
     * Register SnsConnector.
     *
     * @return void
     */
    protected function registerSnsConnector()
    {
        $this->app->singleton('sns.connector', function () {
            return new SnsConnector;
        });
    }

    /**
     * Register SnsConnector.
     *
     * @return void
     */
    protected function registerSnsConnection()
    {
        $this->app->singleton('sns.connection', function ($app) {
            /** @var \Amranidev\MicroBus\Sns\SnsManager $manager */
            $manager = $app['sns'];
            return $manager->connection();
        });
    }

    /**
     * Publish configuration file.
     *
     * @return void
     */
    protected function publishConfiguration()
    {
        $configPath = __DIR__.'/../../config/publisher.php';
        $this->publishes([
            $configPath => base_path('config/publisher.php'), ]);
    }
}
