<?php

namespace Sfynx\CoreBundle\Generator\Presentation\Adapter;

use Sfynx\CoreBundle\Generator\Application\Command\ConsoleCommandInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Interface ConsoleCommandAdapterInterface
 * @package Sfynx\CoreBundle\Generator\Presentation\Adapter
 */
interface ConsoleCommandAdapterInterface
{
    /**
     * ConsoleCommandAdapterInterface constructor.
     * @param string $command
     * @param OutputInterface $output
     */
    public function __construct(string $command, OutputInterface $output);

    /**
     * @param InputInterface $input
     * @return ConsoleCommandInterface
     */
    public function createCommandFromRequest(InputInterface $input): ConsoleCommandInterface;
}