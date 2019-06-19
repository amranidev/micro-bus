<?php

return [
    'sns'    => [
        'key'    => env('PUBLISHER_SNS_KEY'),
        'secret' => env('PUBLISHER_SNS_SECRET'),
        'region' => env('PUBLISHER_SNS_REGION'),
    ],
    'events' => [
        'user_created' => 'TopicArn',
    ],
];
