<?php

namespace Amranidev\MicroBus\Sns;

class SnsManager
{
    /**
     * The application instance.
     *
     * @var \Illuminate\Foundation\Application
     */
    public $app;

    /**
     * @var SnsConnector
     */
    protected $connection;

    /**
     * SnsManager constructor.
     *
     * @param \Illuminate\Foundation\Application $app
     */
    public function __construct($app)
    {
        $this->app = $app;
    }

    /**
     * @return \Amranidev\MicroBus\Sns\Publisher|\Amranidev\MicroBus\Sns\SnsConnector
     */
    public function connection()
    {
        if (!$this->connection)
            $this->connection = $this->resolve();

        return $this->connection;
    }

    /**
     * @return \Amranidev\MicroBus\Sns\Publisher
     */
    protected function resolve()
    {
        $config = $this->getConfig();
        return (new SnsConnector)->connect($config);
    }

    /**
     * @todo update configuration after creating a dedicated package.
     */
    public function getConfig()
    {
        return $this->app['config']['publisher.sns'];
    }
}
