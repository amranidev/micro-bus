<?php

namespace Amranidev\MicroBus\Sns;

use Illuminate\Support\Arr;

abstract class BaseSnsConnector
{
    /**
     * Establish a new SNS/SNS_FIFO connection.
     *
     * @param $config
     *
     * @return Publisher|PublisherFifo
     */
    abstract public function connect($config);

    /**
     * Prepare AWS credentials config.
     *
     * @param $config
     *
     * @return array
     */
    public function prepareCredentialsConfig($config)
    {
        $config = $this->getDefaultConfiguration($config);

        if (!empty($config['key']) && !empty($config['secret'])) {
            $config['credentials'] = Arr::only($config, ['key', 'secret']);
        }

        return $config;
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
