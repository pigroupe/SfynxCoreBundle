<?php

namespace Sfynx\CoreBundle\Generator\Presentation\Adapter;

use Sfynx\CoreBundle\Generator\Application\Command\ConsoleCommandInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ConsoleCommandAdapter implements ConsoleCommandAdapterInterface
{
    /** @var OutputInterface */
    protected $output;

    /** @var string  */
    protected $command;

    public function __construct(string $command, OutputInterface $output)
    {
        $this->output = $output;

        if (!in_array(ConsoleCommandInterface::class, class_implements($command))) {
            throw new \InvalidArgumentException(
                sprintf('Command %s should implement interface %s', $command, ConsoleCommandInterface::class)
            );
        }

        $this->command = $command;
    }

    public function createCommandFromRequest(InputInterface $input): ConsoleCommandInterface
    {
        return new $this->command(array_merge($input->getArguments(), $input->getOptions()), $this->output);
    }
}