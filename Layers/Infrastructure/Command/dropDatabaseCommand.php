<?php

namespace Sfynx\CoreBundle\Layers\Infrastructure\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand as containerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class dropDatabaseCommand extends containerAwareCommand
{
    /**
     * @var object $em THe entity manager service
     * @access protected
     */
    protected $em;

    /**
     * The configure method
     *
     * @access protected
     * @return void
     */
    protected function configure()
    {
        $this
            ->setName('sfynx:tables:drop')
            ->setDescription('Drop all tables of database');
    }

    /**
     * The execute method
     *
     * @param InputInterface  $input  The Input class
     * @param OutputInterface $output The output class
     *
     * @access protected
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->em   = $this->getContainer()->get('doctrine')->getManager('default');
        $connection = $this->em->getConnection();

        $connection->prepare(sprintf('SET FOREIGN_KEY_CHECKS = 0;'))->execute();
        $tables = $connection->getSchemaManager()->listTableNames();
        foreach ($tables as $tableName) {
            $connection->prepare(sprintf('TRUNCATE TABLE %s', $tableName))->execute();
        }
        $connection->prepare(sprintf('SET FOREIGN_KEY_CHECKS = 1;'))->execute();
    }
}
