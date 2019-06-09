<?php

namespace Amranidev\MicroBus\Sqs\Pipeline;

class VerifySubscription
{
    /**
     * Determine if the subscriber is subscribed to the incoming topic.
     *
     * @param array    $body
     * @param \Closure $next
     *
     * @return null
     */
    public function handle($body, \Closure $next)
    {
        /** @var \Amranidev\MicroBus\Sqs\JobMap $jobMap */
        $map = app('jobmap');

        try {
            // Get the job handler Through the the JonMap.
            $handler = $map->fromTopic($body['TopicArn']);
        } catch (\Exception $e) {
            return;
        }

        $body['handler'] = $handler;

        return $next($body);
    }
}
