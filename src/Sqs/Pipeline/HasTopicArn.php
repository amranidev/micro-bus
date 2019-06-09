<?php

namespace Amranidev\MicroBus\Sqs\Pipeline;

class HasTopicArn
{
    /**
     * Determine if the TopicArn exists in the response body.
     *
     * @param array    $body
     * @param \Closure $next
     *
     * @return mixed|null
     */
    public function handle($body, \Closure $next)
    {
        if (!isset($body['TopicArn'])) {
            return;
        }

        return $next($body);
    }
}
