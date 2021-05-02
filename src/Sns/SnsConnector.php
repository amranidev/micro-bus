<?php

namespace Amranidev\MicroBus\Sns;

use Aws\Sns\SnsClient;

class SnsConnector extends BaseSnsConnector
{
    /**
     * Establish a new SNS Connection.
     *
     * @param array $config
     *
     * @return \Amranidev\MicroBus\Sns\Publisher
     */
    public function connect($config)
    {
        return new Publisher(new SnsClient($this->prepareCredentialsConfig($config)));
    }
}
