<?php

namespace Amranidev\MicroBus\Sqs;

class JobMap
{
    /**
     * The underlying topics job map.
     *
     * @var array
     */
    protected $map;

    /**
     * JobMap constructor.
     *
     * @param array $map
     */
    public function __construct(array $map)
    {
        $this->map = $map;
    }

    /**
     * Get the job class from a given topic.
     *
     * @param string $topic
     *
     * @return string
     * @throws \Exception
     */
    public function fromTopic(string $topic): string
    {
        $job = array_search($topic, $this->map);
        if (! $job) {
            throw new \Exception("Topic {$topic} doesn't exists.");
        }
        return $job;
    }
}