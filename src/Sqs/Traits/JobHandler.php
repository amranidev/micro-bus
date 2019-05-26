<?php

namespace Amranidev\MicroBus\Sqs\Traits;

use Illuminate\Queue\Jobs\Job;

/**
 * @method void handle()
 */
trait JobHandler
{
    /**
     * Execute the job.
     *
     * @param \Illuminate\Queue\Jobs\Job $job
     * @param mixed|null $data
     *
     * @throws \Exception
     */
    public function fire(Job $job, $data)
    {
        if (!method_exists($this, 'handle')) {
            throw new \Exception(__CLASS__ . '@handle does not exists!');
        }

        $this->setJob($job);
        $this->setPayload($data);

        $this->handle();

        $job->delete();
    }

    /**
     * Set the queue job.
     *
     * @param $job
     */
    protected function setJob($job)
    {
        if (property_exists($this, 'job')) {
            $this->job = $job;
        }
    }

    /**
     * Set the payload.
     *
     * @param $payload
     */
    protected function setPayload($payload)
    {
        if (property_exists($this, 'payload')) {
            $this->payload = $payload;
        }
    }
}