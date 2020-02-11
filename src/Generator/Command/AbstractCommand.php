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

abstract class AbstractCommand extends Command
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

    protected function save(string $path, string $content)
    {
        $dir = pathinfo($path, PATHINFO_DIRNAME);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        file_put_contents($path, $content);
    }
}