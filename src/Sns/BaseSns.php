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
     * The message is serializable by default.
     *
     * @var bool
     */
    protected $serializable = true;

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
     * Set message serialization to false.
     *
     * @return \Amranidev\MicroBus\Sns\Publisher
     */
    public function withoutSerializing()
    {
        $this->serializable = false;

        return $this;
    }

    /**
     * Determine if the message is serializable.
     *
     * @return bool
     */
    public function isSerializable()
    {
        return $this->serializable ? true : false;
    }

    /**
     * Prepare the message to send.
     *
     * @param mixed $message
     *
     * @return string
     */
    protected function prepareMessage($message)
    {
        if ($this->isSerializable()) {
            return serialize($message);
        }

        return $message;
    }

    /**
     * Retrieve the TopicArn form the config.
     *
     * @param string $topic
     *
     * @throws \Exception
     *
     * @return mixed
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
