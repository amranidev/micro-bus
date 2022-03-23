<?php

namespace Amranidev\MicroBus\Sns;

use Ramsey\Uuid\Uuid;
use Illuminate\Support\Str;

class PublisherFifo extends BaseSns
{
    /**
     * Publish Message to SNS fifo.
     *
     * @param $topic
     * @param $message
     * @param array $payload
     *
     * @throws \Exception
     *
     * @return \Aws\Result
     */
    public function publish($topic, $message, array $payload = [])
    {
        $topic = $this->getTopicArn($topic);
        $message = $this->prepareMessage($message);

        return $this->sns->publish(
            collect(
                [
                    'Message'                => $message,
                    'TopicArn'               => $topic,
                    'MessageGroupId'         => Uuid::uuid4()->toString(),
                    'MessageDeduplicationId' => base64_encode($message),
                    'MessageAttributes' => [
                        'MICRO_BUS.JOB_UUID' => [
                            'DataType' => 'String',
                            'StringValue' => (string) Str::uuid(),
                        ]
                    ],
                ]
            )
                ->merge($payload)
                ->unique()
                ->toArray()
        );
    }
}
