<?php

namespace Amranidev\MicroBus\Tests\Sqs;

use Amranidev\MicroBus\Sqs\Contracts\Handleable;
use Amranidev\MicroBus\Sqs\Traits\JobHandler as Handler;

class JobHandler implements Handleable
{
    use Handler;

    /**
     * @var mixed
     */
    public $payload;

    /**
     * @var \Illuminate\Queue\Jobs\Job
     */
    public $job;

    /**
     * Execute the job.
     *
     * @return mixed
     */
    public function handle()
    {
        //
    }
}
