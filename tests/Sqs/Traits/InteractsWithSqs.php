<?php

namespace Amranidev\MicroBus\Tests\Sqs\Traits;

use Aws\Sqs\SqsClient;

trait InteractsWithSqs
{
    /**
     * Send message to SQS.
     *
     * @param string $message
     *
     * @return void
     */
    public function sendMessage($message)
    {
        $client = $this->makeSqsClient();

        /** @var \Illuminate\Config\Repository $config */
        $config = $this->getConfig();

        $queueName = $config->get('queue.connections.subscriber.queue');
        $queueEndpoint = $config->get('queue.connections.subscriber.prefix').$queueName;

        $client->createQueue(['QueueName' => $queueName]);
        $client->purgeQueue(['QueueUrl' => $queueEndpoint]);

        $client->sendMessage([
            'QueueUrl'    => $queueEndpoint,
            'MessageBody' => $message,
        ]);
    }

    /**
     * Make a new SQS Client.
     *
     * @return \Aws\Sqs\SqsClient
     */
    protected function makeSqsClient(): SqsClient
    {
        $client = new SqsClient([
            'endpoint'    => 'http://localhost:4566',
            'version'     => 'latest',
            'region'      => 'us-east-1',
            'credentials' => [
                'key'    => 'foo',
                'secret' => 'bar',
            ],
        ]);

        return $client;
    }
}
