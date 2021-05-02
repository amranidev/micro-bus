<?php

namespace Amranidev\MicroBus\Sns;

use Ramsey\Uuid\Uuid;

class PublisherFifo extends BaseSns
{
    /**
     * Publish Message to SNS fifo
     *
     * @param $topic
     * @param $message
     * @param array $payload
     * @return \Aws\Result
     * @throws \Exception
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
                ]
            )
                ->merge($payload)
                ->unique()
                ->toArray()
        );
    }
}
