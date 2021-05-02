<?php

namespace Amranidev\MicroBus\Sns;

class SnsFifoManager extends Manager
{
    /**
     * Resolve publisher.
     *
     * @return \Amranidev\MicroBus\Sns\PublisherFifo
     */
    protected function resolve()
    {
        $config = $this->getConfig();

        return (new SnsFifoConnector())->connect($config);
    }
}
