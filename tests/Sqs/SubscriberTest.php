<?php

namespace Amranidev\MicroBus\Tests\Sqs;

use Amranidev\MicroBus\Tests\TestCase;
use Illuminate\Queue\Events\JobProcessed;
use Illuminate\Contracts\Container\Container;
use Amranidev\MicroBus\Tests\Sqs\Traits\InteractsWithSqs;

class SubscriberTest extends TestCase
{
    use InteractsWithSqs;

    /**
     * The job handler instance.
     *
     * @var \Illuminate\Queue\Jobs\Job
     */
    public $job;

    /**
     * Set up.
     */
    protected function setUp(): void
    {
        parent::setUp();

    }

    /** @test */
    public function work_incoming_messages_published_by_sns()
    {
        $this->sendMessage(file_get_contents(__DIR__ . '/messages/message.json'));

        // Listen for the job to be processed.
        $this->app['events']->listen(JobProcessed::class, function ($event) {
            $this->job = $event->job;
        });

        // Run work command.
        $this->artisan('queue:work', ['--once' => true]);

        // Get the underlying subscriber job (JobHandler).
        $subscriber = $this->job->getInstance();

        // Command job should not be null.
        self::assertNotNull($subscriber);

        // Decode the payload.
        $payload = json_decode($subscriber->payload);

        self::assertEquals(
            'Hooray! you have a message!',
            $payload->body
        );

        // The subscriber job should be an instance of JobHandler.
        self::assertInstanceOf(JobHandler::class, $subscriber);

        // JobHandler@handle should instantiate Container using IOC.
        self::assertInstanceOf(Container::class, $subscriber->container);

        // Job should be deleted
        self::assertTrue($this->job->isDeleted());
    }

    /** @test */
    public function ignore_other_messages_with_different_topic_arn()
    {
        /** @var \Illuminate\Config\Repository $config */
        $config = $this->app['config'];

        $this->sendMessage(file_get_contents(__DIR__ . '/messages/message.json'));

        // Listen for the job to be processed.
        $this->app['events']->listen(JobProcessed::class, function ($event) {
            $this->job = $event->job;
        });

        // Set local subscriber class.
        $config->set('subscriber.subscribers.' . JobHandler::class, 'arn:aws:sns:eu-west-1:s1111111111:my-topic-arn2');
        $this->artisan('queue:work', ['--once' => true]);

        // The worker should ignore the message and the job should be null.
        self::assertNull($this->job);
    }

    /** @test */
    public function ignore_messages_with_no_topic_arn()
    {
        // Listen for the job to be processed.
        $this->app['events']->listen(JobProcessed::class, function ($event) {
            $this->job = $event->job;
        });

        $this->sendMessage(file_get_contents(__DIR__ . '/messages/without_topic_arn.json'));

        $this->artisan('queue:work', ['--once' => true]);

        self::assertNull($this->job);
    }
}
