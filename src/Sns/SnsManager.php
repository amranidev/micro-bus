<?php

namespace Amranidev\MicroBus\Sns;

class SnsManager extends Manager
{
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
}
