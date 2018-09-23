<?php

namespace Sfynx\CoreBundle\Generator\Presentation\Adapter;

use Sfynx\CoreBundle\Generator\Application\Command\ConsoleCommandInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

interface ConsoleCommandAdapterInterface
{
    public function __construct(string $command, OutputInterface $output);

    public function createCommandFromRequest(InputInterface $input): ConsoleCommandInterface;
}