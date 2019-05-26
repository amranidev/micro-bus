<?php

namespace Amranidev\MicroBus\Sqs;

use Aws\Sqs\SqsClient;
use Illuminate\Queue\Jobs\SqsJob as AbstractSqsJob;

class SqsJob extends AbstractSqsJob
{
    /**
     * @var string
     */
    protected $handler;

    /**
     * SnsJob constructor.
     *
     * @param $container
     * @param \Aws\Sqs\SqsClient $sqs
     * @param array $job
     * @param string $connectionName
     * @param string $queue
     * @param $handler
     */
    public function __construct($container, SqsClient $sqs, array $job, string $connectionName, string $queue, $handler)
    {
        parent::__construct($container, $sqs, $job, $connectionName, $queue);
        $this->handler = $handler;
    }

    /**
     * @return array
     */
    public function payload()
    {
        $payload = parent::payload();

        $payload['job'] = $this->handler;

        $payload['data'] = unserialize($payload['Message']);

        return $payload;
    }
}