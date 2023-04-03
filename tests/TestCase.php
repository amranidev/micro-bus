<?php

namespace Amranidev\MicroBus\Tests;

use Amranidev\MicroBus\Tests\Sqs\JobHandler;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

abstract class TestCase extends OrchestraTestCase
{
    /**
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageAliases($app)
    {
        return [
            //
        ];
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return ['Amranidev\MicroBus\Sqs\SqsServiceProvider'];
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     */
    protected function getEnvironmentSetUp($app)
    {
        /** @var \Illuminate\Config\Repository $config */
        $config = $app['config'];

        // Queue
        $config->set('queue.default', 'subscriber');
        $config->set('queue.connections.subscriber.key', 'foo');
        $config->set('queue.connections.subscriber.secret', 'bar');
        $config->set('queue.connections.subscriber.prefix', 'http://localhost:4566/111111111111/');
        $config->set('queue.connections.subscriber.region', 'us-east-1');
        $config->set('queue.connections.subscriber.queue', 'micro-bus');
        $config->set('queue.connections.subscriber.driver', 'subscriber');
        $config->set('subscriber.subscribers', [JobHandler::class => 'arn:aws:sns:eu-west-1:111111111111:my-topic-arn']);
    }

    /**
     * @return \Illuminate\Config\Repository
     */
    public function getConfig()
    {
        /** @var \Illuminate\Config\Repository $config */
        $config = $this->app['config'];

        return $config;
    }
}
