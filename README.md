<h1 align="center">
   Build your laravel microservices with micro-bus
</h1>

<p align="center">
   <a href="https://github.styleci.io/repos/190308907">
      <img src="https://github.styleci.io/repos/190308907/shield?branch=master" alt="StyleCI">
   </a>
  <a href="https://travis-ci.org/amranidev/micro-bus" target="_blank">
    <img src="https://travis-ci.org/amranidev/micro-bus.svg?branch=master">
  </a>
</p>

## What is micro-bus?

**MicroBus** is a laravel/lumen package for building microservices with event-driven architecture (Pub-Sub) using Amazon web services (SNS/SQS).

## What is event-driven microservices?

- [Edmunds: Event-Driven, Serverless, and Cost-Effective Enterprise Message Bus
](https://www.youtube.com/watch?v=snuKfIaufP0)

- [Event-Driven Pub-Sub with SNS and SQS](https://www.youtube.com/watch?v=c_WNBmEc6EE)

- [GOTO 2017 • The Many Meanings of Event-Driven Architecture • Martin Fowler
](https://www.youtube.com/watch?v=STKCRSUsyP0)

- [GOTO 2018 • Pragmatic Event-Driven Microservices • Allard Buijze](https://www.youtube.com/watch?v=vSd_0zGxsIU)

- [Building Scalable Applications and Microservices: Adding Messaging to Your Toolbox](https://aws.amazon.com/blogs/compute/building-scalable-applications-and-microservices-adding-messaging-to-your-toolbox/)

## Installation.

### Laravel.

1. Install the package, `composer require amranidev/micro-bus`.

2. Publish the subscriber config file, `php artisan vendor:publish --tag=subscriber`.

3. Publish the publisher config file, `php artisan vendor:publish --tag=publisher`.

4. Add the subscriber and the publisher environment variables.

   - In the `.env` file add.

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
   
 5. Add the Queue connection configuration in `config/queue.php`.
 
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
 
Congratulations you have successfully installed **micro-bus** :rocket:

### Lumen.


1. Install the package, `composer require amranidev/micro-bus`.

2. Add the subscriber and the publisher environment variables.


   - In the `.env` file add.

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

3. Create `config` folder in the root directory.

4. Create `subscriber.php` in the config folder.

```php
<?php

return [
   'subscribers' => [
      '__CLASSNAME__' => 'TopicArn'
   ]
];
```

5. Create `publisher.php` in the config folder.

```php
<?php

return [
    'sns'    => [
        'key'    => env('PUBLISHER_SNS_KEY'),
        'secret' => env('PUBLISHER_SNS_SECRET'),
        'region' => env('PUBLISHER_SNS_REGION'),
    ],
    'events' => [
        'user_created' => 'arn:aws:sns:eu-west-1:111111111111:user_created'
    ]
];

```

6. Create `queue.php` in the config folder.

Copy the same `queue.php` from [laravel/laravel](https://github.com/laravel/laravel/blob/master/config/queue.php) and add the subscriber configuration into `connections`.

```php
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
7. Configure `subscriber`, `publisher`, `queue` and register the ServiceProvider in `bootstrap/app.php`.

```php

$app->configure('publisher')
$app->configure('subscriber');
$app->configure('queue');

$app->register(Amranidev\MicroBus\MicroBusServiceProvider::class);
```

Congratulations you have successfully installed **micro-bus** in lumen :rocket:

## Usage.

> Note that you need to define your Topics in SNS, create the queues in SQS and tie
SNS topics to the queues, **AWS configuration is not included in this documentation.**

After installing the package in both laravel nodes, now, let's make them talk to each other.
Basically, they can be both subscribers or publishers at the same time, or, you can make one 
as subscriber and the other as publisher, it is entirely up to you.

Let's say we have a laravel app called **A** and a lumen microservice called **B**.

When a user is created in the **A**, **B** needs to add the user's email to the mailing list,

In this case **A** is the publisher and **B** is the subscriber.

> Note that the SNS configuration needs to be added to **A** and the SQS 
needs to be added in **B**, see installation steps above.

#### Setup Laravel app (Microservice A).

First of all, let's add the topic that we've created in AWS to **A**, 
we need to specify an event name `user_created` and the AWS **TopicArn** in `config/publisher.php`

```php
<?php

return [
    'sns'    => [
        'key'    => env('PUBLISHER_SNS_KEY'),
        'secret' => env('PUBLISHER_SNS_SECRET'),
        'region' => env('PUBLISHER_SNS_REGION'),
    ],
    'events' => [
        'user_created' => 'arn:aws:sns:eu-west-1:111111111111:user_created'
    ]
];

```

After we configured the SNS in **A**, we need to add the functionality, 
when a user is created, we need to publish a message to SNS, to do so:

```php
...
Publisher::publish('user_created', $user);
...
```

#### Set up Lumen (Microservice B).

Now, let's create the subscriber class which will be listening for **A** in **B**.

- `php artisan make:subscriber UserCreated`

This command will scaffold a job class for you in `app/Subscribers/`.

```php
<?php

namespace App\Subscribers;

use Amranidev\MicroBus\Sqs\Traits\JobHandler;

class UserCreated
{
    use JobHandler;

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
     * @return void
     */
    public function handle()
    {
        //
    }
}
```

As you can see, the job created has `$payload` property, it 
is simply the piece of data that **A** will be publishing, in our case will be the `$user`.

The **Handle** method is the one responsible for executing the job coming form SQS,

```php
/**
 * Execute the job.
 *
 * @return void
 */
public function handle(MailingList $mailingList)
{
    $user = $this->payload;
    
    // MailingList is resolved automatically from the container.
    $mailingList->addUser($user->name, $user->email);
}
```

The last thing we need to do in **B** is to tie this class to the **TopicArn** in `config/subscriber.php`.

```php
<?php

return [
    'subscribers' => [
        \App\Subscribers\UserCreated::class => 'arn:aws:sns:eu-west-1:111111111111:user_created'
    ]
];
```

And run the `queue:work` command, `php artisan queue:work --queue=subscriber`,

## Contributing.

Thank you for considering contributing to this project! The contribution guide can be found in [Contribution guide](CONTRIBUTING.md).

## Testing.

- Pull the [localstack/localstack](https://github.com/localstack/localstack) docker image.

`docker pull localstack/localstack`

Create a `docker-compose.yml` file.

```yml
version: '2.1'

services:
  localstack:
    image: localstack/localstack
    ports:
      - "4567-4593:4567-4593"
      - "${PORT_WEB_UI-8080}:${PORT_WEB_UI-8080}"
    environment:
      - SERVICES=sqs,sns
      - DEBUG=${DEBUG- }
      - DATA_DIR=${DATA_DIR- }
      - PORT_WEB_UI=${PORT_WEB_UI- }
      - LAMBDA_EXECUTOR=${LAMBDA_EXECUTOR- }
      - KINESIS_ERROR_PROBABILITY=${KINESIS_ERROR_PROBABILITY- }
      - DOCKER_HOST=unix:///var/run/docker.sock
    volumes:
      - "${TMPDIR:-/tmp/localstack}:/tmp/localstack"
      - "/var/run/docker.sock:/var/run/docker.sock"
```

- Run `docker-compose run` to start localstack, if you're using mac run `TMPDIR=/private$TMPDIR docker-compose up`.

- Finally run `phpunit`.
