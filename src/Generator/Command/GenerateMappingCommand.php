<?php
/**
 * User: boshurik
 * Date: 2019-07-17
 * Time: 13:47
 */

namespace BoShurik\MapperBundle\Generator\Command;

use BoShurik\MapperBundle\Generator\Helper\ClassPathResolver;
use BoShurik\MapperBundle\Generator\Helper\ClassQuestionFactory;
use BoShurik\MapperBundle\Generator\MappingGenerator;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateMappingCommand extends AbstractCommand
{
    /**
     * @var MappingGenerator
     */
    private $generator;

    /**
     * @var ClassPathResolver
     */
    private $pathResolver;

    /**
     * @var ClassQuestionFactory
     */
    private $questionFactory;

    public function __construct(MappingGenerator $generator, ClassPathResolver $pathResolver, ClassQuestionFactory $questionFactory)
    {
        parent::__construct(null);

        $this->generator = $generator;
        $this->pathResolver = $pathResolver;
        $this->questionFactory = $questionFactory;
    }

    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this
            ->setName('mapper:generate:mapping')
            ->addArgument('name', InputArgument::REQUIRED, 'Mapping FQCN')
            ->addArgument('source', InputArgument::REQUIRED, 'Source FQCN')
            ->addArgument('destination', InputArgument::REQUIRED, 'Destination FQCN')
            ->addOption('reverse', null, InputOption::VALUE_NONE, 'Reverse mapping')
            ->setDescription('Generates mapping based on given objects')
        ;
    }

    /**
     * @inheritDoc
     */
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        $askReverse = 3;
        if (!$input->getArgument('name')) {
            $input->setArgument('name', $this->io->askQuestion($this->questionFactory->create('Mapping FQCN')));
            $askReverse--;
        }
        if (!$input->getArgument('source')) {
            $input->setArgument('source', $this->io->askQuestion($this->questionFactory->create('Source FQCN')));
            $askReverse--;
        }
        if (!$input->getArgument('destination')) {
            $input->setArgument('destination', $this->io->askQuestion($this->questionFactory->create('Destination FQCN')));
            $askReverse--;
        }
        if ($askReverse === 0) {
            $input->setOption('reverse', $this->io->confirm('Generate two-way mapping?'));
        }
    }

    /**
     * @inheritDoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->save(
            $this->pathResolver->resolve($input->getArgument('name')),
            $this->generator->generate(
                $input->getArgument('name'),
                $input->getArgument('source'),
                $input->getArgument('destination'),
                $input->getOption('reverse')
            )
        );

        return 0;
    }
}