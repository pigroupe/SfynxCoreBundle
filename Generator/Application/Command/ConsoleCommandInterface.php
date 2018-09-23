<?php

namespace Sfynx\CoreBundle\Generator\Application\Command;

use Symfony\Component\Console\Output\OutputInterface;

interface ConsoleCommandInterface
{
    public function __construct(array $args, OutputInterface $output);
}
