<?php

namespace Amranidev\MicroBus\Sqs;

use Aws\Sqs\SqsClient;
use Illuminate\Queue\SqsQueue as AbstractSqsQueue;

class SqsQueue extends AbstractSqsQueue
{
    /**
     * @var \App\Services\Components\Sns\JobMap
     */
    protected $map;

    /**
     * SnsQueue constructor.
     *
     * @param \Aws\Sqs\SqsClient $sqs
     * @param string $default
     * @param $prefix
     * @param \Amranidev\MicroBus\Sqs\JobMap $map
     */
    public function __construct(SqsClient $sqs, string $default, $prefix, JobMap $map)
    {
        parent::__construct($sqs, $default, $prefix);
        $this->map = $map;
    }

    /**
     * @param null $queue
     *
     * @return \App\Services\Components\Sns\SnsJob|\Illuminate\Contracts\Queue\Job|null
     */
    public function pop($queue = null)
    {
        // First we will pop the messages available form the queue.
        $response = $this->sqs->receiveMessage([
            'QueueUrl'       => $queue = $this->getQueue($queue),
            'AttributeNames' => ['ApproximateReceiveCount'],
        ]);

        return $this->processMessage($queue ,$response);
    }

    /**
     * @param $queue
     * @param $response
     *
     * @return \Amranidev\MicroBus\Sqs\SqsJob
     */
    protected function processMessage($queue, $response)
    {
        // if the messages are not empty then we proceed
        if (!is_null($response['Messages']) && count($response['Messages']) > 0) {
            $body = json_decode($response['Messages'][0]['Body'], true);

            // If the message body has not `TopicArn` attribute, ignore this message
            if (!isset($body['TopicArn'])) {
                return null;
            }

            try {
                // Get the job handler Through the the JonMap.
                $handler = $this->map->fromTopic($body['TopicArn']);
            } catch (\Exception $e) {
                // Ignore if the job not found.
                return null;
            }

            // Proceed Sqs Job
            return new SqsJob(
                $this->container, $this->sqs, $response['Messages'][0],
                $this->connectionName, $queue, $handler
            );
        }

        return null;
    }
}