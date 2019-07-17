<?php
/**
 * User: boshurik
 * Date: 2019-07-18
 * Time: 13:35
 */

namespace BoShurik\MapperBundle\Generator\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class AbstractCommand extends Command
{
    /**
     * @var SymfonyStyle
     */
    protected $io;

    /**
     * @inheritDoc
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->io = new SymfonyStyle($input, $output);
    }
}