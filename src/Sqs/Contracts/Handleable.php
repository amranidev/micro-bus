<?php

namespace Amranidev\MicroBus\Sqs\Contracts;

interface Handleable
{
    /**
     * @return mixed
     */
    public function handle();
}
