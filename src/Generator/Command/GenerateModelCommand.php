<?php
/**
 * User: boshurik
 * Date: 2019-07-17
 * Time: 13:47
 */

namespace BoShurik\MapperBundle\Generator\Command;

use BoShurik\MapperBundle\Generator\Helper\ClassPathResolver;
use BoShurik\MapperBundle\Generator\Helper\ClassQuestionFactory;
use BoShurik\MapperBundle\Generator\ModelGenerator;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateModelCommand extends AbstractCommand
{
    /**
     * @var ModelGenerator
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

    public function __construct(ModelGenerator $generator, ClassPathResolver $pathResolver, ClassQuestionFactory $questionFactory)
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
            ->setName('mapper:generate:model')
            ->addArgument('name', InputArgument::REQUIRED, 'Model FQCN')
            ->addArgument('class', InputArgument::REQUIRED, 'Source FQCN')
            ->setDescription('Generates model based on given object')
        ;
    }

    /**
     * @inheritDoc
     */
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        if (!$input->getArgument('name')) {
            $input->setArgument('name', $this->io->askQuestion($this->questionFactory->create('Model FQCN')));
        }
        if (!$input->getArgument('class')) {
            $input->setArgument('class', $this->io->askQuestion($this->questionFactory->create('Source FQCN')));
        }
    }

    /**
     * @inheritDoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        file_put_contents(
            $this->pathResolver->resolve($input->getArgument('name')),
            $this->generator->generate(
                $input->getArgument('name'),
                $input->getArgument('class')
            )
        );
    }
}