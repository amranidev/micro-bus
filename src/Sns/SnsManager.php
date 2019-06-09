<?php

namespace Amranidev\MicroBus\Sns;

class SnsManager
{
    /**
     * The application instance.
     *
     * @var \Illuminate\Contracts\Container\Container
     */
    public $app;

    /**
     * The underlying SNS connection.
     *
     * @var SnsConnector
     */
    protected $connection;

    /**
     * SnsManager constructor.
     *
     * @param \Illuminate\Contracts\Container\Container $app
     */
    public function __construct($app)
    {
        $this->app = $app;
    }

    /**
     * Establish SNS connection.
     *
     * @return \Amranidev\MicroBus\Sns\Publisher
     */
    public function connection()
    {
        if (!$this->connection) {
            $this->connection = $this->resolve();
        }

        return $this->connection;
    }

    /**
     * Resolve publisher.
     *
     * @return \Amranidev\MicroBus\Sns\Publisher
     */
    protected function resolve()
    {
        $config = $this->getConfig();

        return (new SnsConnector())->connect($config);
    }

    /**
     * Get SNS configuration.
     *
     * @return array
     */
    public function getConfig()
    {
        return $this->app['config']['publisher.sns'];
    }
}
