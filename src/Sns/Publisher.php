<?php

namespace Amranidev\MicroBus\Sns;

class Publisher
{
    /**
     * @var \Aws\Sns\SnsClient
     */
    protected $sns;

    /**
     * @var \Illuminate\Config\Repository|mixed
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
     * @param $topic
     * @param $message
     *
     * @return \Aws\Result
     * @throws \Exception
     */
    public function publish($topic, $message)
    {
        $topic = $this->processTopic($topic);

        return $this->sns->publish([
            'Message'  => serialize($message),
            'TopicArn' => $topic,
        ]);
    }

    /**
     * @param $topic
     *
     * @return mixed
     * @throws \Exception
     */
    protected function processTopic($topic)
    {
        if ($this->topics->has($topic)) {
            return $this->topics->get($topic);
        }

        if ($this->topics->search($topic, true)) {
            return $topic;
        }

        throw new \Exception('Unknown Topic');
    }
}
