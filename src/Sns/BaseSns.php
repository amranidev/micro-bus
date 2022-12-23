<?php

namespace Amranidev\MicroBus\Sns;

abstract class BaseSns
{
    /**
     * SNS client.
     *
     * @var \Aws\Sns\SnsClient
     */
    protected $sns;

    /**
     * The underlying topics collection.
     *
     * @var \Illuminate\Support\Collection
     */
    protected $topics;

    /**
     * Publisher constructor.
     *
     * @param $sns
     */
    public function __construct($sns)
    {
        $this->sns = $sns;

        $this->topics = collect(config('publisher.events'));
    }

    /**
     * Prepare the message to send.
     * Default wrapper for the message to send.
     *
     * @param mixed $message
     *
     * @return string
     */
    protected function prepareMessage($message)
    {
        return $message;
    }

    /**
     * Retrieve the TopicArn form the config.
     *
     * @param string $topic
     *
     * @return mixed
     * @throws \Exception
     */
    protected function getTopicArn($topic)
    {
        if ($this->topics->has($topic)) {
            return $this->topics->get($topic);
        }

        if ($this->topics->search($topic, true)) {
            return $topic;
        }

        throw new \Exception("Topic or event ({$topic}) doesn't exists");
    }

    /**
     * Get SNS client.
     *
     * @return \Aws\Sns\SnsClient
     */
    public function getSns()
    {
        return $this->sns;
    }
}
