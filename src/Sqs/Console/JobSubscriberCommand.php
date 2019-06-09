<?php

namespace Amranidev\MicroBus\Sqs\Console;

use Illuminate\Console\GeneratorCommand;

class JobSubscriberCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:subscriber';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new subscriber class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'subscriber';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__.'/stubs/subscriber.stub';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param string $rootNamespace
     *
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\Subscribers';
    }
}
