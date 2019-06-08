<h1 align="center">
   Build your laravel microservices with micro-bus
</h1>

<p align="center">
  <a href="https://travis-ci.org/amranidev/micro-bus" target="_blank">
    <img src="https://travis-ci.org/amranidev/micro-bus.svg?branch=master">
  </a>
</p>

## What is micro-bus?

**MicroBus** is a laravel/lumen package for building microservices with event-driven architecture (Pub-Sub) using Amazon web services (SNS/SQS).

## What is event-driven microservices?

- [Event-Driven Pub-Sub with SNS and SQS](https://www.youtube.com/watch?v=c_WNBmEc6EE)

- [Building Scalable Applications and Microservices: Adding Messaging to Your Toolbox](https://aws.amazon.com/blogs/compute/building-scalable-applications-and-microservices-adding-messaging-to-your-toolbox/)

## Getting started.

### Laravel.

1. Install the package, `composer require amranidev/micro-bus`

2. Publish the subscriber config file, `php artisan vendor:publish --tag=subscriber`

3. Publish the publisher config file, `php artisan vendor:publish --tag=publisher`

4. Add the subscriber and the publisher environment variables.

   - In the .env add

   ```
   SUBSCRIBER_SQS_KEY=<SQS-KEY-AWS>
   SUBSCRIBER_SQS_SECRET=<SQS-SECRET-AWS>
   SUBSCRIBER_SQS_PREFIX=https://sqs.<sqs-region>.amazonaws.com/<project-id>
   SUBSCRIBER_SQS_QUEUE=<QUEUE-NAME-AWS>
   SUBSCRIBER_SQS_REGION=<SQS-REGION-AWS>
    
   PUBLISHER_SNS_KEY=<SNS-KEY-AWS>
   PUBLISHER_SNS_SECRET=<SNS-KEY-AWS>
   PUBLISHER_SNS_REGION=<SNS-REGION-AWS>
   ```
   
 5. Add the Queue configuration in `config/queue.php` inside the `connections`.
 
    ```
    'subscriber' => [
        'driver'      => 'subscriber',
        'key'         => env('SUBSCRIBER_SQS_KEY', 'your-public-key'),
        'secret'      => env('SUBSCRIBER_SQS_SECRET', 'your-secret-key'),
        'prefix'      => env('SUBSCRIBER_SQS_PREFIX', 'https://sqs.us-east-1.amazonaws.com/your-account-id'),
        'queue'       => env('SUBSCRIBER_SQS_QUEUE', 'your-queue-name'),
        'region'      => env('SUBSCRIBER_SQS_REGION', 'us-east-1'),
        'retry_after' => 90,
    ],
    ```

...

## Contributing.

...

## Testing.

...
