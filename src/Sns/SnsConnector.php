<?php

namespace Amranidev\MicroBus\Sns;

use Aws\Sns\SnsClient;
use Illuminate\Support\Arr;

class SnsConnector
{
    /**
     * @param $config
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
     * @param $config
     *
     * @return array
     */
    public function getDefaultConfiguration($config)
    {
        return array_merge([
            'profile' => 'default',
            'version' => 'latest',
        ], $config);
    }
}