<?php

namespace Amranidev\MicroBus\Sqs\Pipeline;

class ValidateResponse
{
    /**
     * Validate the incoming response from the queue.
     *
     * @param array    $response
     * @param \Closure $next
     *
     * @return mixed|null
     */
    public function handle($response, \Closure $next)
    {
        if (!isset($response['Messages'])) {
            return;
        }

        if (is_null($response['Messages']) && count($response['Messages']) == 0) {
            return;
        }

        $body = json_decode($response['Messages'][0]['Body'], true);

        return $next($body);
    }
}
