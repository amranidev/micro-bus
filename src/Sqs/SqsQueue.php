<?php

namespace Amranidev\MicroBus\Sqs;

use Aws\Sqs\SqsClient;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Queue\SqsQueue as AbstractSqsQueue;

class SqsQueue extends AbstractSqsQueue
{
    /**
     * The underlying pipeline.
     *
     * @var array
     */
    protected $pipeline = [
        \Amranidev\MicroBus\Sqs\Pipeline\ValidateResponse::class,
        \Amranidev\MicroBus\Sqs\Pipeline\HasTopicArn::class,
        \Amranidev\MicroBus\Sqs\Pipeline\VerifySubscription::class,
    ];

    /**
     * SnsQueue constructor.
     *
     * @param \Aws\Sqs\SqsClient $sqs
     * @param string             $default
     * @param $prefix
     */
    public function __construct(SqsClient $sqs, string $default, $prefix)
    {
        parent::__construct($sqs, $default, $prefix);
    }

    /**
     * Pop the next job off of the queue.
     *
     * @param null $queue
     *
     * @return \Amranidev\MicroBus\Sqs\SqsJob|null
     */
    public function pop($queue = null)
    {
        // First we will pop the messages available form the queue.
        $response = $this->sqs->receiveMessage([
            'QueueUrl'       => $queue = $this->getQueue($queue),
            'AttributeNames' => ['ApproximateReceiveCount'],
        ]);

        return $this->processResponse($queue, $response);
    }

    /**
     * Process queue response.
     *
     * @param $queue
     * @param \Aws\Result $response
     *
     * @return \Amranidev\MicroBus\Sqs\SqsJob|null
     */
    protected function processResponse($queue, $response)
    {
        $message = $this->pipe($response->toArray());

        if (is_null($message)) {
            return;
        }

        return new SqsJob(
            $this->container, $this->sqs, $response['Messages'][0],
            $this->connectionName, $queue, $message['handler']
        );
    }

    /**
     * Process the queue response through the pipeline.
     *
     * @param array $response
     *
     * @return mixed
     */
    protected function pipe($response)
    {
        return (new Pipeline($this->container))->send($response)
            ->through($this->pipeline)
            ->then(function ($message) {
                return $message;
            });
    }
}
