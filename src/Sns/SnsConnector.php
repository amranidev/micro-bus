<?php

namespace Amranidev\MicroBus\Sns;

use Aws\Sns\SnsClient;
use Illuminate\Support\Arr;

class SnsConnector
{
    /**
     * Establish an SNS Connection.
     *
     * @param array $config
     *
     * @return \Amranidev\MicroBus\Sns\Publisher
     */
    public function connect($config)
    {
        $config = $this->getDefaultConfiguration($config);

        $config['credentials'] = Arr::only($config, ['key', 'secret']);

        return new Publisher(new SnsClient($config));
    }

    /**
     * Get the default configuration.
     *
     * @param array $config
     *
     * @return array
     */
    public function getDefaultConfiguration($config)
    {
        return array_merge([
            'version' => 'latest',
        ], $config);
    }
}
