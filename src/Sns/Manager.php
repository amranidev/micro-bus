<?php

namespace Amranidev\MicroBus\Sns;

abstract class Manager
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
     * Get SNS configuration.
     *
     * @return array
     */
    public function getConfig()
    {
        return $this->app['config']['publisher.sns'];
    }

    /**
     * Establish SNS connection.
     *
     * @return \Amranidev\MicroBus\Sns\BaseSns
     */
    public function connection()
    {
        if (!$this->connection) {
            $this->connection = $this->resolve();
        }

        return $this->connection;
    }
}
