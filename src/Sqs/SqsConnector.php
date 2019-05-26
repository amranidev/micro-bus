<?php

namespace Amranidev\MicroBus\Sqs;

use Aws\Sqs\SqsClient;
use Illuminate\Support\Arr;
use Illuminate\Queue\Connectors\ConnectorInterface;
use Illuminate\Queue\Connectors\SqsConnector as AbstractSqsConnector;

class SqsConnector extends AbstractSqsConnector implements ConnectorInterface
{
    /**
     * @var \App\Services\Components\Sns\JobMap
     */
    protected $map;

    /**
     * SnsConnector constructor.
     *
     * @param \Amranidev\MicroBus\Sqs\JobMap $map
     */
    public function __construct(JobMap $map)
    {
        $this->map = $map;
    }

    /**
     * @param array $config
     *
     * @return \App\Services\Components\Sns\SnsQueue|\Illuminate\Contracts\Queue\Queue
     */
    public function connect(array $config)
    {
        $config = $this->getDefaultConfiguration($config);
        if ($config['key'] && $config['secret']) {
            $config['credentials'] = Arr::only($config, ['key', 'secret', 'token']);
        }

        return new SqsQueue(
            new SqsClient($config), $config['queue'], $config['prefix'], $this->map
        );
    }
}