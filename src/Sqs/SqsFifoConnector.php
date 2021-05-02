<?php

namespace Amranidev\MicroBus\Sqs;

use Aws\Sqs\SqsClient;
use Illuminate\Support\Arr;

class SqsFifoConnector extends \Illuminate\Queue\Connectors\SqsConnector
{
    /**
     * Establish a SQS fifo queue connection.
     *
     * @param array $config
     *
     * @return \Amranidev\MicroBus\Sqs\SqsQueue
     */
    public function connect(array $config)
    {
        $config = $this->getDefaultConfiguration($config);

        if (!empty($config['key']) && !empty($config['secret'])) {
            $config['credentials'] = Arr::only($config, ['key', 'secret']);
        }

        return new SqsQueue(
            new SqsClient($config),
            $config['queue'],
            Arr::get($config, 'prefix', '')
        );
    }
}
