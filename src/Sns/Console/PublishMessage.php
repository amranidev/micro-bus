<?php

namespace Amranidev\MicroBus\Sns\Console;

use Illuminate\Console\Command;

class PublishMessage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bus:publish
                            {message : Message to be published}
                            {event : Event or topic name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish new message to SNS';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        /** @var \Amranidev\MicroBus\Sns\Publisher $publisher */
        $publisher = app('sns.connection');

        try {
            $publisher->publish($this->argument('event'), $this->argument('message'));
            $this->info('Message published successfully!');
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }
}
