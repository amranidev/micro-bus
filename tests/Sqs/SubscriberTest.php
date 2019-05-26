<?php

namespace Amranidev\MicroBus\Tests\Sqs;

use Amranidev\MicroBus\Tests\TestCase;
use Illuminate\Queue\Events\JobProcessed;
use Amranidev\MicroBus\Tests\Sqs\Traits\InteractsWithSqs;

class SubscriberTest extends TestCase
{
    use InteractsWithSqs;

    /**
     * The job handler instance.
     *
     * @var mixed
     */
    public $job;

    /**
     * Set up.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->sendMessage(file_get_contents(__DIR__ . '/messages/message.json'));
    }

    /** @test */
    public function work_incoming_messages_published_by_sns()
    {
        /** @var \Illuminate\Config\Repository $config */
        $config = $this->app['config'];

        $this->app['events']->listen(JobProcessed::class, function ($event) {
            $this->job = json_decode($event->job->getInstance()->payload);
        });

        $this->artisan('queue:work', ['--once' => true]);

        self::assertEquals(
            'Hooray! you have a message!',
            $this->job->body
        );
    }

    /** @test */
    public function work_only_subscribed_jobs()
    {
        /** @var \Illuminate\Config\Repository $config */
        $config = $this->app['config'];

        $this->app['events']->listen(JobProcessed::class, function ($event) {
            $this->job = $event->job->getInstance();
        });

        $config->set('subscriber.subscribers.'. JobHandler::class, 'arn:aws:sns:eu-west-1:s1111111111:my-topic-arn2');
        $this->artisan('queue:work', ['--once' => true]);

        self::assertNull($this->job);
    }
}
