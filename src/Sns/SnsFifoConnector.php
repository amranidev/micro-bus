<?php

namespace Amranidev\MicroBus\Sns;

use Aws\Sns\SnsClient;

class SnsFifoConnector extends BaseSnsConnector
{
    /**
     * Establish a new fifo SNS Connection.
     *
     * @param array $config
     *
     * @return \Amranidev\MicroBus\Sns\PublisherFifo
     */
    public function connect($config)
    {
        return new PublisherFifo(new SnsClient($this->prepareCredentialsConfig($config)));
    }
}
