<?php

return [
    'sns'    => [
        'key'    => env('SNS_KEY'),
        'secret' => env('SNS_SECRET'),
        'region' => env('SNS_REGION'),
    ],
    'events' => [
        'user_created' => 'ARN'
    ]
];
