<?php

namespace Amranidev\MicroBus\Tests\Sqs;

use Illuminate\Container\Container;
use Amranidev\MicroBus\Sqs\Traits\JobHandler as Handler;

class JobHandler
{
    use Handler;

    /**
     * @var mixed
     */
    public $payload;

    /**
     * @var
     */
    public $container;

    /**
     * @var \Illuminate\Queue\Jobs\Job
     */
    public $job;

    /**
     * Execute the job.
     *
     * @param \Illuminate\Container\Container $container
     *
     * @return mixed
     */
    public function handle(Container $container)
    {
        $this->container = $container;
    }
}
